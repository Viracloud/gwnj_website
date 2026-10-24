<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use \App\Models\Concerns\SavesExtraLocales;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'surname',
        'photo',
        'email',
        'password',
        'position',
        'position_fa',
        'occupation',
        'occupation_fa',
        'phone',
        'address',
        'country',
        'state',
        'city',
        'zip',
        'membership_status',
        'membership_type',
        'membership_source',
        'membership_id',
        'membership_applied_at',
        'membership_approved_at',
        'membership_rejected_reason',
        'dues_paid',
        'dues_exempt_override',
        'card_status',
        'card_requested_at',
        'card_issued_at',
        'card_expires_at',
        'verification_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'membership_applied_at' => 'datetime',
            'membership_approved_at' => 'datetime',
            'card_requested_at' => 'datetime',
            'card_issued_at' => 'datetime',
            'card_expires_at' => 'datetime',
            'dues_paid' => 'boolean',
            'dues_exempt_override' => 'boolean',
        ];
    }

    public function isApprovedMember(): bool
    {
        return $this->membership_status === 'approved';
    }

    /** Members based in Iran, Afghanistan or Pakistan are exempt from dues (admin can override per-member). */
    public function isDuesExempt(): bool
    {
        if (!is_null($this->dues_exempt_override)) {
            return (bool) $this->dues_exempt_override;
        }

        $exempt_countries = [
            'iran', 'islamic republic of iran', 'ايران', 'ایران',
            'afghanistan', 'afghanestan', 'afqanistan', 'افغانستان',
            'pakistan', 'islamic republic of pakistan', 'پاکستان',
        ];

        return in_array(mb_strtolower(trim((string) $this->country)), $exempt_countries, true);
    }

    /** Dues must be paid before a card is issued, unless the member is exempt. */
    public function canReceiveCard(): bool
    {
        return $this->isDuesExempt() || $this->dues_paid;
    }

    /** An issued card that has passed its expiry date (cards are valid for one year). */
    public function cardIsExpired(): bool
    {
        return $this->card_status === 'issued' && $this->card_expires_at && $this->card_expires_at->isPast();
    }

    /** Issued and not yet expired. */
    public function cardIsValid(): bool
    {
        return $this->card_status === 'issued' && !$this->cardIsExpired();
    }

    /**
     * For a renewal, members who pay dues must pay again for the new year:
     * a dues payment recorded after the current card was issued counts.
     */
    public function duesPaidForRenewal(): bool
    {
        $amount = optional(\App\Models\Setting::where('id', 1)->first())->membership_dues_amount;
        if ($this->isDuesExempt() || !$amount) {
            return true;
        }
        return \App\Models\Donation::where('message', 'Membership dues - '.$this->membership_id)
            ->where('status', 'Completed')
            ->when($this->card_issued_at, fn ($q) => $q->where('created_at', '>', $this->card_issued_at))
            ->exists();
    }

    public function profileIsComplete(): bool
    {
        foreach (['name', 'surname', 'photo', 'phone', 'address', 'country', 'state', 'city', 'zip'] as $field) {
            if (trim((string) $this->{$field}) === '') {
                return false;
            }
        }
        return true;
    }

    public function fullName(): string
    {
        return trim($this->name . ' ' . $this->surname);
    }

    /** The committees this member belongs to - a person can be on more than one. */
    public function committees()
    {
        return $this->belongsToMany(MemberCategory::class, 'user_committee')->orderBy('sort_order');
    }
}
