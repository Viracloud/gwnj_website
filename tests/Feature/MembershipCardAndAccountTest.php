<?php

use App\Models\Admin;
use App\Models\Donation;
use App\Models\MemberCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    DB::table('settings')->updateOrInsert(['id' => 1], ['membership_dues_amount' => 20, 'paypal_currency' => 'USD']);
});

function member(array $attrs = []): User
{
    return User::factory()->create(array_merge([
        'password' => 'secret-pass-1',
        'surname' => 'Test', 'photo' => 'p.jpg', 'phone' => '1', 'address' => 'a',
        'country' => 'Germany', 'state' => 's', 'city' => 'c', 'zip' => '1',
        'membership_status' => 'approved', 'membership_id' => 'GWJN-'.uniqid(),
        'card_status' => 'not_requested', 'dues_paid' => false,
    ], $attrs));
}

function expiredCard(array $attrs = []): User
{
    return member(array_merge([
        'dues_paid' => true, 'dues_paid_at' => now()->subYear()->subWeek(),
        'card_status' => 'issued', 'card_issued_at' => now()->subYear()->subDay(),
        'card_expires_at' => now()->subDay(), 'verification_token' => 'tok'.uniqid(),
    ], $attrs));
}

function superAdmin(): Admin
{
    return Admin::create(['name' => 'A', 'email' => 'admin'.uniqid().'@x.test', 'password' => bcrypt('x'), 'role' => 'super_admin']);
}

// ---------- 2. Account deletion ----------

it('deletes the account with the right password and confirmation', function () {
    $u = member();
    $cat = MemberCategory::forceCreate(['name' => 'Media Committee', 'slug' => 'media-committee-'.uniqid(), 'sort_order' => 1]);
    $u->committees()->attach($cat->id);
    file_put_contents(public_path('uploads/test_delete_photo.jpg'), 'x');
    $u->update(['photo' => 'test_delete_photo.jpg']);

    // A "remember me" token must not bring the deleted row back on logout.
    $u->forceFill(['remember_token' => 'remember-me-token'])->save();

    $this->actingAs($u)->post('/en/account/delete', ['delete_password' => 'secret-pass-1', 'confirm_delete' => '1'])
        ->assertRedirect('/en');

    expect(User::find($u->id))->toBeNull()
        ->and(DB::table('user_committee')->where('user_id', $u->id)->exists())->toBeFalse()
        ->and(file_exists(public_path('uploads/test_delete_photo.jpg')))->toBeFalse();
    $this->assertGuest('web');
});

it('refuses deletion with a wrong password', function () {
    $u = member();
    $this->actingAs($u)->post('/en/account/delete', ['delete_password' => 'wrong', 'confirm_delete' => '1'])
        ->assertSessionHasErrors('delete_password');
    expect(User::find($u->id))->not->toBeNull();
});

it('refuses deletion without the confirmation tick', function () {
    $u = member();
    $this->actingAs($u)->post('/en/account/delete', ['delete_password' => 'secret-pass-1'])
        ->assertSessionHasErrors('confirm_delete');
    expect(User::find($u->id))->not->toBeNull();
});

it('does not let a guest delete anything', function () {
    $this->post('/en/account/delete', ['delete_password' => 'x', 'confirm_delete' => '1'])->assertRedirect();
    $this->assertGuest('web');
});

// ---------- 3. Free for Iran / Afghanistan / Pakistan ----------

it('exempts Iran, Afghanistan and Pakistan from dues, not other countries', function (string $country, bool $exempt) {
    expect(member(['country' => $country])->isDuesExempt())->toBe($exempt);
})->with([
    ['Afghanistan', true], ['afghanistan ', true], ['افغانستان', true],
    ['Iran', true], ['ایران', true], ['Pakistan', true], ['پاکستان', true],
    ['Germany', false], ['Sweden', false], ['Turkey', false],
]);

it('lets an exempt member request a first card without paying', function () {
    $u = member(['country' => 'Afghanistan']);
    $this->actingAs($u)->post('/en/membership/card/request')->assertSessionHas('success');
    expect($u->fresh()->card_status)->toBe('requested');
});

it('makes a member from another country pay before the first card', function () {
    $u = member();
    $this->actingAs($u)->post('/en/membership/card/request')->assertSessionHas('error');
    expect($u->fresh()->card_status)->toBe('not_requested');

    $u->update(['dues_paid' => true, 'dues_paid_at' => now()]);
    $this->actingAs($u)->post('/en/membership/card/request')->assertSessionHas('success');
});

// ---------- 4. One-year card ----------

it('lets a member open a valid card', function () {
    $u = member(['country' => 'Afghanistan', 'card_status' => 'issued', 'card_issued_at' => now(),
        'card_expires_at' => now()->addYear(), 'verification_token' => 't'.uniqid()]);
    expect($u->cardIsValid())->toBeTrue();
    // The page itself needs the full site data, so only check it is not redirected away.
    $res = $this->actingAs($u)->get('/en/membership/card');
    expect($res->isRedirect())->toBeFalse();
});

it('blocks opening or printing an expired card', function () {
    $u = expiredCard();
    $this->actingAs($u)->get('/en/membership/card')
        ->assertRedirect('/en/dashboard')->assertSessionHas('error');
});

it('needs a new dues payment to renew an expired card', function () {
    $u = expiredCard();
    $this->actingAs($u)->post('/en/membership/card/request')->assertSessionHas('error');
    expect($u->fresh()->card_status)->toBe('issued');

    $u->update(['dues_paid_at' => now()]);
    $this->actingAs($u)->post('/en/membership/card/request')->assertSessionHas('success');
    expect($u->fresh()->card_status)->toBe('requested');
});

it('still accepts an online dues payment recorded only as a donation (older payments)', function () {
    $u = expiredCard(['dues_paid_at' => null]);
    Donation::forceCreate(['donor_name' => 'x', 'donor_email' => 'x@x.test', 'message' => 'Membership dues - '.$u->membership_id,
        'amount' => 20, 'currency' => 'USD', 'payment_method' => 'PayPal', 'status' => 'Completed']);
    expect($u->fresh()->canIssueCard())->toBeTrue();
});

it('renews an expired card of an exempt member without payment', function () {
    $u = expiredCard(['country' => 'Pakistan', 'dues_paid' => false, 'dues_paid_at' => null]);
    $this->actingAs($u)->post('/en/membership/card/request')->assertSessionHas('success');
});

it('does not let the admin issue a renewal directly before the new dues are paid', function () {
    $u = expiredCard();
    $this->actingAs(superAdmin(), 'admin')->post('/admin/membership/card-issue-direct/'.$u->id)->assertSessionHas('error');
    expect($u->fresh()->card_expires_at->isPast())->toBeTrue();
});

it('lets the admin mark dues paid and then renew the card for one more year', function () {
    $u = expiredCard();
    $admin = superAdmin();
    // Currently "paid" (last year): one click unmarks, the next marks paid today.
    $this->actingAs($admin, 'admin')->post('/admin/membership/toggle-dues/'.$u->id);
    expect($u->fresh()->dues_paid_at)->toBeNull();
    $this->actingAs($admin, 'admin')->post('/admin/membership/toggle-dues/'.$u->id);
    expect($u->fresh()->dues_paid_at)->not->toBeNull();

    $this->actingAs($admin, 'admin')->post('/admin/membership/card-issue-direct/'.$u->id)->assertSessionHas('success');
    $u->refresh();
    expect($u->cardIsValid())->toBeTrue()
        ->and((int) round(now()->diffInDays($u->card_expires_at)))->toBeBetween(364, 366);
});

it('needs the dues paid again after the renewed card is issued (one payment = one year)', function () {
    $u = expiredCard(['dues_paid_at' => now()->subMinute()]);
    expect($u->canIssueCard())->toBeTrue();
    $u->update(['card_issued_at' => now(), 'card_expires_at' => now()->addYear()]);
    expect($u->fresh()->duesPaidForRenewal())->toBeFalse();
});
