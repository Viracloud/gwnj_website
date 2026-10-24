<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Front\MembershipController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminSliderController;
use App\Http\Controllers\Admin\AdminPhotoController;
use App\Http\Controllers\Admin\AdminFaqController;
use App\Http\Controllers\Admin\AdminAboutItemController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminCampaignController;
use App\Http\Controllers\Admin\AdminMemberController;
use App\Http\Controllers\Admin\AdminMembershipController;
use App\Http\Controllers\Admin\AdminMemberCategoryController;
use App\Http\Controllers\Admin\AdminPostCategoryController;
use App\Http\Controllers\Admin\AdminEventCategoryController;
use App\Http\Controllers\Admin\AdminCampaignCategoryController;
use App\Http\Controllers\Admin\AdminPublicationCategoryController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminPageHomeItemController;
use App\Http\Controllers\Admin\AdminHomeTextController;
use App\Http\Controllers\Admin\AdminPageAboutItemController;
use App\Http\Controllers\Admin\AdminPageEventItemController;
use App\Http\Controllers\Admin\AdminPageBlogItemController;
use App\Http\Controllers\Admin\AdminPageTeamItemController;
use App\Http\Controllers\Admin\AdminPageGalleryItemController;
use App\Http\Controllers\Admin\AdminPageFaqItemController;
use App\Http\Controllers\Admin\AdminPageLoginItemController;
use App\Http\Controllers\Admin\AdminPageUserItemController;
use App\Http\Controllers\Admin\AdminPageTermsItemController;
use App\Http\Controllers\Admin\AdminPagePrivacyItemController;
use App\Http\Controllers\Admin\AdminPageContactItemController;
use App\Http\Controllers\Admin\AdminPageDonateItemController;
use App\Http\Controllers\Admin\AdminDonationController;
use App\Http\Controllers\Admin\AdminSubscriberController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminCustomPageController;
use App\Http\Controllers\Admin\AdminPublicationController;
use App\Http\Controllers\Admin\AdminPartnerController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminAccountController;

Route::get('/robots.txt', [FrontController::class, 'robots'])->name('robots');
Route::get('/sitemap.xml', [FrontController::class, 'sitemap'])->name('sitemap');

// "/" → the visitor's language (or the default); /lang/xx keeps old switcher links working.
Route::get('/', fn () => redirect('/'.preferred_locale()));
Route::get('/lang/{locale}', [FrontController::class, 'switch_lang'])->name('switch_lang');

/*
| Every public page lives under its language: /en/..., /fa/... (later /fr/..., /de/...).
| Only switched-on languages (SITE_LOCALES in .env) match; the SetLocale middleware
| applies the language and removes {locale} before the controller is called.
*/
Route::prefix('{locale}')
    ->where(['locale' => implode('|', array_map('preg_quote', config('locales.enabled')))])
    ->middleware(\App\Http\Middleware\SetLocale::class)
    ->group(function () {

Route::get('/', [FrontController::class, 'home'])->name('home');
Route::get('/about', [FrontController::class, 'about'])->name('about');
Route::get('/login', [FrontController::class, 'login'])->name('login');
Route::post('/login',[FrontController::class,'login_submit'])->name('login_submit')->middleware('throttle:forms5');
Route::get('/register', [FrontController::class, 'register'])->name('register');
Route::post('/register_submit',[FrontController::class,'register_submit'])->name('register_submit')->middleware('throttle:forms5');
Route::get('/register_verify/{token}/{email}',[FrontController::class,'register_verify'])->name('register_verify');
Route::get('/forget-password',[FrontController::class,'forget_password'])->name('forget_password');
Route::post('/forget-password',[FrontController::class,'forget_password_submit'])->name('forget_password_submit')->middleware('throttle:forms3');
Route::get('/reset-password/{token}/{email}',[FrontController::class,'reset_password'])->name('reset_password');
Route::post('/reset-password/{token}/{email}',[FrontController::class,'reset_password_submit'])->name('reset_password_submit')->middleware('throttle:forms5');
Route::get('/gallery', [FrontController::class, 'gallery'])->name('gallery');
Route::get('/faq', [FrontController::class, 'faq'])->name('faq');
Route::get('/publications', [FrontController::class, 'publications'])->name('publications');
Route::get('/partners', [FrontController::class, 'partners'])->name('partners');

Route::get('/events', [FrontController::class, 'events'])->name('events');
Route::get('/event/{slug}', [FrontController::class, 'event'])->name('event');
Route::get('/campaigns', [FrontController::class, 'campaigns'])->name('campaigns');
Route::get('/campaign/{slug}', [FrontController::class, 'campaign'])->name('campaign');

Route::get('/team-members', [FrontController::class, 'members'])->name('members');
Route::get('/team-member/{slug}', [FrontController::class, 'member'])->name('member');

Route::get('/blog', [FrontController::class, 'blog'])->name('blog');
Route::get('/post/{slug}', [FrontController::class, 'post'])->name('post');
Route::get('/category/{slug}', [FrontController::class, 'category'])->name('category');
Route::get('/archive/{month}', [FrontController::class, 'archive'])->name('archive');
Route::get('/tag/{tag}', [FrontController::class, 'tag'])->name('tag');
Route::get('/search', [FrontController::class, 'search'])->name('search');

Route::post('/comment/store',[FrontController::class,'comment_store'])->name('comment_store')->middleware('throttle:forms5');
Route::post('/reply/store',[FrontController::class,'reply_store'])->name('reply_store')->middleware('throttle:forms5');

Route::get('/terms-of-use', [FrontController::class, 'terms'])->name('terms');
Route::get('/privacy-policy', [FrontController::class, 'privacy'])->name('privacy');
Route::get('/contact', [FrontController::class, 'contact'])->name('contact');
Route::post('/contact', [FrontController::class, 'contact_submit'])->name('contact_submit')->middleware('throttle:forms3');

Route::get('/donate', [FrontController::class, 'donate'])->name('donate');
Route::post('/donate', [FrontController::class, 'donate_submit'])->name('donate_submit')->middleware('throttle:forms10');
Route::get('/donate/success', [FrontController::class, 'donate_success'])->name('donate_success');
Route::get('/donate/cancel', [FrontController::class, 'donate_cancel'])->name('donate_cancel');

Route::post('/subscriber/send-email', [FrontController::class, 'subscriber_send_email'])->name('subscriber_send_email')->middleware('throttle:forms5');
Route::get('/subscriber/verify/{email}/{token}', [FrontController::class, 'subscriber_verify'])->name('subscriber_verify');

Route::get('/page/{slug}', [FrontController::class, 'custom_page'])->name('custom_page');

Route::get('/invite/{token}', [MembershipController::class, 'invite_show'])->name('invite_show');
Route::post('/invite/{token}', [MembershipController::class, 'invite_submit'])->name('invite_submit')->middleware('throttle:forms5');
Route::get('/verify/{token}', [MembershipController::class, 'verify'])->name('membership_verify');
Route::get('/membership', [MembershipController::class, 'info'])->name('membership_info');
Route::get('/polls', [\App\Http\Controllers\Front\PollController::class, 'index'])->name('polls');
Route::get('/poll/{slug}', [\App\Http\Controllers\Front\PollController::class, 'show'])->name('poll');
Route::post('/poll/{slug}/vote', [\App\Http\Controllers\Front\PollController::class, 'vote'])->name('poll_vote')->middleware('throttle:forms10');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard',[UserController::class,'dashboard'])->name('dashboard');
    Route::get('/profile',[UserController::class,'profile'])->name('profile');
    Route::post('/profile',[UserController::class,'profile_update'])->name('profile_update');
    Route::get('/logout',[UserController::class,'logout'])->name('logout');
    Route::post('/membership/card/request', [MembershipController::class, 'card_request'])->name('membership_card_request');
    Route::get('/membership/card', [MembershipController::class, 'card'])->name('membership_card');
    Route::get('/membership/dues/pay', [MembershipController::class, 'dues_pay'])->name('membership_dues_pay');
    Route::get('/membership/dues/success', [MembershipController::class, 'dues_success'])->name('membership_dues_success');
    Route::get('/membership/dues/cancel', [MembershipController::class, 'dues_cancel'])->name('membership_dues_cancel');
});

    }); // end of /{locale} group

// Old addresses without a language (/events, /post/..., /verify/... on printed cards,
// links in old e-mails) → 301 to the same page with a language prefix.
Route::fallback(function (\Illuminate\Http\Request $request) {
    return \App\Http\Middleware\RedirectToLocalizedUrl::redirect($request) ?? abort(404);
});


Route::prefix('admin')->group(function () {
    Route::get('/', function () {return redirect('/admin/login');});
    Route::get('/login',[AdminController::class,'login'])->name('admin_login');
    Route::post('/login',[AdminController::class,'login_submit'])->name('admin_login_submit')->middleware('throttle:forms5');
    Route::get('/logout',[AdminController::class,'logout'])->name('admin_logout');
    Route::get('/forget-password',[AdminController::class,'forget_password'])->name('admin_forget_password');
    Route::post('/forget_password_submit',[AdminController::class,'forget_password_submit'])->name('admin_forget_password_submit')->middleware('throttle:forms3');
    Route::get('/reset-password/{token}/{email}',[AdminController::class,'reset_password'])->name('admin_reset_password');
    Route::post('/reset-password/{token}/{email}',[AdminController::class,'reset_password_submit'])->name('admin_reset_password_submit')->middleware('throttle:forms5');
});

Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/dashboard',[AdminController::class,'dashboard'])->name('admin_dashboard');
    Route::get('/profile',[AdminController::class,'profile'])->name('admin_profile');
    Route::post('/profile',[AdminController::class,'profile_update'])->name('admin_profile_update');

    // ===== Content: viewable by every role (super_admin, author, viewer) =====
    Route::middleware('role:super_admin,author,viewer')->group(function () {
        Route::get('/slider/index',[AdminSliderController::class,'index'])->name('admin_slider_index');
        Route::get('/photo/index',[AdminPhotoController::class,'index'])->name('admin_photo_index');
        Route::get('/faq/index',[AdminFaqController::class,'index'])->name('admin_faq_index');
        Route::get('/about-item/index',[AdminAboutItemController::class,'index'])->name('admin_about_item_index');
        Route::get('/event/index',[AdminEventController::class,'index'])->name('admin_event_index');
        Route::get('/event/{event_id}/faqs', [AdminEventController::class, 'faqs'])->name('admin_event_faqs');
        Route::get('/campaign/index',[AdminCampaignController::class,'index'])->name('admin_campaign_index');
        Route::get('/member-category/index',[AdminMemberCategoryController::class,'index'])->name('admin_member_category_index');
        Route::get('/member/index',[AdminMemberController::class,'index'])->name('admin_member_index');
        Route::get('/member/{member_id}/awards', [AdminMemberController::class, 'awards'])->name('admin_member_awards');
        Route::get('/post-category/index',[AdminPostCategoryController::class,'index'])->name('admin_post_category_index');
        Route::get('/event-category/index',[AdminEventCategoryController::class,'index'])->name('admin_event_category_index');
        Route::get('/campaign-category/index',[AdminCampaignCategoryController::class,'index'])->name('admin_campaign_category_index');
        Route::get('/poll/index',[\App\Http\Controllers\Admin\AdminPollController::class,'index'])->name('admin_poll_index');
        Route::get('/poll/{id}/results',[\App\Http\Controllers\Admin\AdminPollController::class,'results'])->name('admin_poll_results');
        Route::get('/publication-category/index',[AdminPublicationCategoryController::class,'index'])->name('admin_publication_category_index');
        Route::get('/post/index',[AdminPostController::class,'index'])->name('admin_post_index');
        Route::get('/post/comments',[AdminPostController::class,'comments'])->name('admin_post_comments');
        Route::get('/post/replies',[AdminPostController::class,'replies'])->name('admin_post_replies');
        Route::get('/page-item/home/index',[AdminPageHomeItemController::class,'index'])->name('admin_page_home_index');
        Route::get('/home-text/index',[AdminHomeTextController::class,'index'])->name('admin_home_text_index');
        Route::get('/page-item/about/index',[AdminPageAboutItemController::class,'index'])->name('admin_page_about_index');
        Route::get('/page-item/event/index',[AdminPageEventItemController::class,'index'])->name('admin_page_event_index');
        Route::get('/page-item/blog/index',[AdminPageBlogItemController::class,'index'])->name('admin_page_blog_index');
        Route::get('/page-item/team/index',[AdminPageTeamItemController::class,'index'])->name('admin_page_team_index');
        Route::get('/page-item/gallery/index',[AdminPageGalleryItemController::class,'index'])->name('admin_page_gallery_index');
        Route::get('/page-item/faq/index',[AdminPageFaqItemController::class,'index'])->name('admin_page_faq_index');
        Route::get('/page-item/login/index',[AdminPageLoginItemController::class,'index'])->name('admin_page_login_index');
        Route::get('/page-item/user/index',[AdminPageUserItemController::class,'index'])->name('admin_page_user_index');
        Route::get('/page-item/terms/index',[AdminPageTermsItemController::class,'index'])->name('admin_page_terms_index');
        Route::get('/page-item/privacy/index',[AdminPagePrivacyItemController::class,'index'])->name('admin_page_privacy_index');
        Route::get('/page-item/contact/index',[AdminPageContactItemController::class,'index'])->name('admin_page_contact_index');
        Route::get('/page-item/donate/index',[AdminPageDonateItemController::class,'index'])->name('admin_page_donate_index');
        Route::get('/custom-page/index',[AdminCustomPageController::class,'index'])->name('admin_custom_page_index');
        Route::get('/custom-page/about-index',[AdminCustomPageController::class,'about_index'])->name('admin_custom_page_about_index');
        Route::get('/custom-page/our-work-index',[AdminCustomPageController::class,'our_work_index'])->name('admin_custom_page_our_work_index');
        Route::get('/custom-page/news-media-index',[AdminCustomPageController::class,'news_media_index'])->name('admin_custom_page_news_media_index');
        Route::get('/custom-page/get-involved-index',[AdminCustomPageController::class,'get_involved_index'])->name('admin_custom_page_get_involved_index');
        Route::get('/custom-page/partnerships-index',[AdminCustomPageController::class,'partnerships_index'])->name('admin_custom_page_partnerships_index');
        Route::get('/custom-page/contact-index',[AdminCustomPageController::class,'contact_index'])->name('admin_custom_page_contact_index');
        Route::get('/custom-page/{custom_page_id}/gallery', [AdminCustomPageController::class, 'gallery'])->name('admin_custom_page_gallery');
        Route::get('/publication/index',[AdminPublicationController::class,'index'])->name('admin_publication_index');
        Route::get('/partner/index',[AdminPartnerController::class,'index'])->name('admin_partner_index');
    });

    // ===== Content: create/edit/delete — super_admin and author only (not viewer) =====
    Route::middleware('role:super_admin,author')->group(function () {
        Route::post('/slider/store',[AdminSliderController::class,'store'])->name('admin_slider_store');
        Route::post('/slider/update/{id}',[AdminSliderController::class,'update'])->name('admin_slider_update');
        Route::post('/slider/delete/{id}',[AdminSliderController::class,'delete'])->name('admin_slider_delete');

        Route::post('/photo/store',[AdminPhotoController::class,'store'])->name('admin_photo_store');
        Route::post('/photo/update/{id}',[AdminPhotoController::class,'update'])->name('admin_photo_update');
        Route::post('/photo/delete/{id}',[AdminPhotoController::class,'delete'])->name('admin_photo_delete');

        Route::post('/faq/store',[AdminFaqController::class,'store'])->name('admin_faq_store');
        Route::post('/faq/update/{id}',[AdminFaqController::class,'update'])->name('admin_faq_update');
        Route::post('/faq/delete/{id}',[AdminFaqController::class,'delete'])->name('admin_faq_delete');

        Route::post('/about-item/update',[AdminAboutItemController::class,'update'])->name('admin_about_item_update');
        Route::post('/about-item/images/store',[AdminAboutItemController::class,'image_store'])->name('admin_about_item_image_store');
        Route::post('/about-item/images/{id}/update',[AdminAboutItemController::class,'image_update'])->name('admin_about_item_image_update');
        Route::post('/about-item/images/{id}/delete',[AdminAboutItemController::class,'image_delete'])->name('admin_about_item_image_delete');

        Route::post('/event/store',[AdminEventController::class,'store'])->name('admin_event_store');
        Route::post('/event/update/{id}',[AdminEventController::class,'update'])->name('admin_event_update');
        Route::post('/event/delete/{id}',[AdminEventController::class,'delete'])->name('admin_event_delete');
        Route::post('/event/{event_id}/faq/store',[AdminEventController::class,'faq_store'])->name('admin_event_faq_store');
        Route::post('/event/{faq_id}/faq/update',[AdminEventController::class,'faq_update'])->name('admin_event_faq_update');
        Route::post('/event/{faq_id}/faq/delete',[AdminEventController::class,'faq_delete'])->name('admin_event_faq_delete');

        Route::post('/campaign/store',[AdminCampaignController::class,'store'])->name('admin_campaign_store');
        Route::post('/campaign/update/{id}',[AdminCampaignController::class,'update'])->name('admin_campaign_update');
        Route::post('/campaign/delete/{id}',[AdminCampaignController::class,'delete'])->name('admin_campaign_delete');

        Route::post('/member-category/store',[AdminMemberCategoryController::class,'store'])->name('admin_member_category_store');
        Route::post('/member-category/update/{id}',[AdminMemberCategoryController::class,'update'])->name('admin_member_category_update');
        Route::post('/member-category/delete/{id}',[AdminMemberCategoryController::class,'delete'])->name('admin_member_category_delete');
        Route::post('/member/store',[AdminMemberController::class,'store'])->name('admin_member_store');
        Route::post('/member/update/{id}',[AdminMemberController::class,'update'])->name('admin_member_update');
        Route::post('/member/delete/{id}',[AdminMemberController::class,'delete'])->name('admin_member_delete');
        Route::post('/member/{member_id}/award/store',[AdminMemberController::class,'award_store'])->name('admin_member_award_store');
        Route::post('/member/{award_id}/award/update',[AdminMemberController::class,'award_update'])->name('admin_member_award_update');
        Route::post('/member/{award_id}/award/delete',[AdminMemberController::class,'award_delete'])->name('admin_member_award_delete');

        Route::post('/post-category/store',[AdminPostCategoryController::class,'store'])->name('admin_post_category_store');
        Route::post('/post-category/update/{id}',[AdminPostCategoryController::class,'update'])->name('admin_post_category_update');
        Route::post('/post-category/delete/{id}',[AdminPostCategoryController::class,'delete'])->name('admin_post_category_delete');

        Route::post('/event-category/store',[AdminEventCategoryController::class,'store'])->name('admin_event_category_store');
        Route::post('/event-category/update/{id}',[AdminEventCategoryController::class,'update'])->name('admin_event_category_update');
        Route::post('/event-category/delete/{id}',[AdminEventCategoryController::class,'delete'])->name('admin_event_category_delete');
        Route::post('/campaign-category/store',[AdminCampaignCategoryController::class,'store'])->name('admin_campaign_category_store');
        Route::post('/campaign-category/update/{id}',[AdminCampaignCategoryController::class,'update'])->name('admin_campaign_category_update');
        Route::post('/campaign-category/delete/{id}',[AdminCampaignCategoryController::class,'delete'])->name('admin_campaign_category_delete');
        Route::get('/poll/create',[\App\Http\Controllers\Admin\AdminPollController::class,'create'])->name('admin_poll_create');
        Route::post('/poll/store',[\App\Http\Controllers\Admin\AdminPollController::class,'store'])->name('admin_poll_store');
        Route::get('/poll/{id}/edit',[\App\Http\Controllers\Admin\AdminPollController::class,'edit'])->name('admin_poll_edit');
        Route::post('/poll/{id}/update',[\App\Http\Controllers\Admin\AdminPollController::class,'update'])->name('admin_poll_update');
        Route::get('/poll/{id}/questions',[\App\Http\Controllers\Admin\AdminPollController::class,'questions'])->name('admin_poll_questions');
        Route::post('/poll/{id}/questions/store',[\App\Http\Controllers\Admin\AdminPollController::class,'question_store'])->name('admin_poll_question_store');
        Route::post('/poll/question/{qid}/update',[\App\Http\Controllers\Admin\AdminPollController::class,'question_update'])->name('admin_poll_question_update');
        Route::post('/poll/question/{qid}/delete',[\App\Http\Controllers\Admin\AdminPollController::class,'question_delete'])->name('admin_poll_question_delete');
        Route::get('/poll/{id}/google',[\App\Http\Controllers\Admin\AdminPollController::class,'google'])->name('admin_poll_google');
        Route::post('/poll/{id}/google/url',[\App\Http\Controllers\Admin\AdminPollController::class,'google_save_url'])->name('admin_poll_google_url');
        Route::post('/poll/{id}/google/columns',[\App\Http\Controllers\Admin\AdminPollController::class,'google_save_columns'])->name('admin_poll_google_columns');
        Route::post('/poll/{id}/google/refresh',[\App\Http\Controllers\Admin\AdminPollController::class,'google_refresh'])->name('admin_poll_google_refresh');
        Route::get('/poll/{id}/export',[\App\Http\Controllers\Admin\AdminPollController::class,'export'])->name('admin_poll_export');

        Route::post('/publication-category/store',[AdminPublicationCategoryController::class,'store'])->name('admin_publication_category_store');
        Route::post('/publication-category/update/{id}',[AdminPublicationCategoryController::class,'update'])->name('admin_publication_category_update');
        Route::post('/publication-category/delete/{id}',[AdminPublicationCategoryController::class,'delete'])->name('admin_publication_category_delete');

        Route::post('/post/store',[AdminPostController::class,'store'])->name('admin_post_store');
        Route::post('/post/update/{id}',[AdminPostController::class,'update'])->name('admin_post_update');
        Route::post('/post/delete/{id}',[AdminPostController::class,'delete'])->name('admin_post_delete');
        Route::post('/post/comment/change/{id}',[AdminPostController::class,'change_comment_status'])->name('admin_post_comment_change');
        Route::post('/post/comment/delete/{id}',[AdminPostController::class,'delete_comment'])->name('admin_post_comment_delete');
        Route::post('/post/reply/change/{id}',[AdminPostController::class,'change_reply_status'])->name('admin_post_reply_change');
        Route::post('/post/reply/delete/{id}',[AdminPostController::class,'delete_reply'])->name('admin_post_reply_delete');
        Route::post('/post/reply/admin-reply',[AdminPostController::class,'admin_reply'])->name('admin_post_reply_admin_reply');

        Route::post('/home-text/update',[AdminHomeTextController::class,'update'])->name('admin_home_text_update');
        Route::post('/page-item/home/update',[AdminPageHomeItemController::class,'update'])->name('admin_page_home_update');
        Route::post('/page-item/about/update',[AdminPageAboutItemController::class,'update'])->name('admin_page_about_update');
        Route::post('/page-item/event/update',[AdminPageEventItemController::class,'update'])->name('admin_page_event_update');
        Route::post('/page-item/blog/update',[AdminPageBlogItemController::class,'update'])->name('admin_page_blog_update');
        Route::post('/page-item/team/update',[AdminPageTeamItemController::class,'update'])->name('admin_page_team_update');
        Route::post('/page-item/gallery/update',[AdminPageGalleryItemController::class,'update'])->name('admin_page_gallery_update');
        Route::post('/page-item/faq/update',[AdminPageFaqItemController::class,'update'])->name('admin_page_faq_update');
        Route::post('/page-item/login/update',[AdminPageLoginItemController::class,'update'])->name('admin_page_login_update');
        Route::post('/page-item/user/update',[AdminPageUserItemController::class,'update'])->name('admin_page_user_update');
        Route::post('/page-item/terms/update',[AdminPageTermsItemController::class,'update'])->name('admin_page_terms_update');
        Route::post('/page-item/privacy/update',[AdminPagePrivacyItemController::class,'update'])->name('admin_page_privacy_update');
        Route::post('/page-item/contact/update',[AdminPageContactItemController::class,'update'])->name('admin_page_contact_update');
        Route::post('/page-item/donate/update',[AdminPageDonateItemController::class,'update'])->name('admin_page_donate_update');

        Route::post('/custom-page/store',[AdminCustomPageController::class,'store'])->name('admin_custom_page_store');
        Route::post('/custom-page/update/{id}',[AdminCustomPageController::class,'update'])->name('admin_custom_page_update');
        Route::post('/custom-page/delete/{id}',[AdminCustomPageController::class,'delete'])->name('admin_custom_page_delete');
        Route::post('/custom-page/{custom_page_id}/gallery/store', [AdminCustomPageController::class, 'gallery_store'])->name('admin_custom_page_gallery_store');
        Route::post('/custom-page/gallery/{image_id}/update', [AdminCustomPageController::class, 'gallery_update'])->name('admin_custom_page_gallery_update');
        Route::post('/custom-page/gallery/{image_id}/delete', [AdminCustomPageController::class, 'gallery_delete'])->name('admin_custom_page_gallery_delete');

        Route::post('/publication/store',[AdminPublicationController::class,'store'])->name('admin_publication_store');
        Route::post('/publication/update/{id}',[AdminPublicationController::class,'update'])->name('admin_publication_update');
        Route::post('/publication/delete/{id}',[AdminPublicationController::class,'delete'])->name('admin_publication_delete');

        Route::post('/partner/store',[AdminPartnerController::class,'store'])->name('admin_partner_store');
        Route::post('/partner/update/{id}',[AdminPartnerController::class,'update'])->name('admin_partner_update');
        Route::post('/partner/delete/{id}',[AdminPartnerController::class,'delete'])->name('admin_partner_delete');
    });

    // ===== Site-wide, financial and personal-data areas — super_admin only =====
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/admin-user/index',[AdminAccountController::class,'index'])->name('admin_account_index');
        Route::get('/setting/languages', function () {
            $languages = [];
            foreach (config('locales.supported') as $code => $meta) {
                $languages[$code] = $meta + [
                    'enabled' => in_array($code, enabled_locales(), true),
                    'prepared' => in_array($code, config('locales.prepare', []), true),
                    'progress' => $code === base_locale() ? null : \App\Support\LocaleColumns::completeness($code),
                ];
            }
            return view('admin.setting.languages', compact('languages'));
        })->name('admin_setting_languages');
        Route::post('/poll/{id}/delete',[\App\Http\Controllers\Admin\AdminPollController::class,'delete'])->name('admin_poll_delete');
        Route::post('/admin-user/store',[AdminAccountController::class,'store'])->name('admin_account_store');
        Route::post('/admin-user/update/{id}',[AdminAccountController::class,'update'])->name('admin_account_update');
        Route::post('/admin-user/delete/{id}',[AdminAccountController::class,'delete'])->name('admin_account_delete');

        Route::get('/user/index',[AdminUserController::class,'index'])->name('admin_user_index');
        Route::post('/user/store',[AdminUserController::class,'store'])->name('admin_user_store');
        Route::post('/user/update/{id}',[AdminUserController::class,'update'])->name('admin_user_update');
        Route::post('/user/delete/{id}',[AdminUserController::class,'delete'])->name('admin_user_delete');

        Route::get('/donation/index',[AdminDonationController::class,'index'])->name('admin_donation_index');
        Route::get('/donation/export',[AdminDonationController::class,'export'])->name('admin_donation_export');
        Route::post('/donation/delete/{id}',[AdminDonationController::class,'delete'])->name('admin_donation_delete');

        Route::get('/subscriber/index',[AdminSubscriberController::class,'index'])->name('admin_subscriber_index');
        Route::post('/subscriber/delete/{id}',[AdminSubscriberController::class,'delete'])->name('admin_subscriber_delete');
        Route::get('/subscriber/export',[AdminSubscriberController::class,'export'])->name('admin_subscriber_export');

        Route::get('/setting/logo',[AdminSettingController::class,'logo'])->name('admin_setting_logo');
        Route::post('/setting/logo/update',[AdminSettingController::class,'logo_update'])->name('admin_setting_logo_update');
        Route::get('/setting/favicon',[AdminSettingController::class,'favicon'])->name('admin_setting_favicon');
        Route::post('/setting/favicon/update',[AdminSettingController::class,'favicon_update'])->name('admin_setting_favicon_update');
        Route::get('/setting/banner',[AdminSettingController::class,'banner'])->name('admin_setting_banner');
        Route::post('/setting/banner/update',[AdminSettingController::class,'banner_update'])->name('admin_setting_banner_update');
        Route::get('/setting/top-bar',[AdminSettingController::class,'top_bar'])->name('admin_setting_top_bar');
        Route::post('/setting/top-bar/update',[AdminSettingController::class,'top_bar_update'])->name('admin_setting_top_bar_update');
        Route::get('/setting/footer',[AdminSettingController::class,'footer'])->name('admin_setting_footer');
        Route::post('/setting/footer/update',[AdminSettingController::class,'footer_update'])->name('admin_setting_footer_update');
        Route::get('/setting/theme-color',[AdminSettingController::class,'theme_color'])->name('admin_setting_theme_color');
        Route::post('/setting/theme-color/update',[AdminSettingController::class,'theme_color_update'])->name('admin_setting_theme_color_update');
        Route::get('/setting/layout-direction',[AdminSettingController::class,'layout_direction'])->name('admin_setting_layout_direction');
        Route::post('/setting/layout-direction/update',[AdminSettingController::class,'layout_direction_update'])->name('admin_setting_layout_direction_update');
        Route::get('/setting/paypal',[AdminSettingController::class,'paypal'])->name('admin_setting_paypal');
        Route::post('/setting/paypal/update',[AdminSettingController::class,'paypal_update'])->name('admin_setting_paypal_update');
        Route::get('/setting/seo',[AdminSettingController::class,'seo'])->name('admin_setting_seo');
        Route::post('/setting/seo/update',[AdminSettingController::class,'seo_update'])->name('admin_setting_seo_update');
        Route::get('/setting/sitemap',[AdminSettingController::class,'sitemap'])->name('admin_setting_sitemap');
        Route::post('/setting/sitemap/update',[AdminSettingController::class,'sitemap_update'])->name('admin_setting_sitemap_update');

        Route::get('/membership/applications',[AdminMembershipController::class,'applications'])->name('admin_membership_applications');
        Route::post('/membership/approve/{id}',[AdminMembershipController::class,'approve'])->name('admin_membership_approve');
        Route::post('/membership/reject/{id}',[AdminMembershipController::class,'reject'])->name('admin_membership_reject');
        Route::get('/membership/members',[AdminMembershipController::class,'members'])->name('admin_membership_members');
        Route::post('/membership/toggle-dues/{id}',[AdminMembershipController::class,'toggle_dues'])->name('admin_membership_toggle_dues');
        Route::post('/membership/update-position/{id}',[AdminMembershipController::class,'update_position'])->name('admin_membership_update_position');
        Route::get('/membership/card-requests',[AdminMembershipController::class,'card_requests'])->name('admin_membership_card_requests');
        Route::post('/membership/card-issue/{id}',[AdminMembershipController::class,'card_issue'])->name('admin_membership_card_issue');
        Route::post('/membership/card-decline/{id}',[AdminMembershipController::class,'card_decline'])->name('admin_membership_card_decline');
        Route::post('/membership/card-issue-direct/{id}',[AdminMembershipController::class,'card_issue_direct'])->name('admin_membership_card_issue_direct');
        Route::get('/membership/card-view/{id}',[AdminMembershipController::class,'card_view'])->name('admin_membership_card_view');
        Route::get('/membership/invitations',[AdminMembershipController::class,'invitations'])->name('admin_membership_invitations');
        Route::post('/membership/invite-store',[AdminMembershipController::class,'invite_store'])->name('admin_membership_invite_store');
        Route::post('/membership/invite-cancel/{id}',[AdminMembershipController::class,'invite_cancel'])->name('admin_membership_invite_cancel');

        Route::get('/menu/index',[AdminMenuController::class,'index'])->name('admin_menu_index');
        Route::post('/menu/update-login',[AdminMenuController::class,'update_login'])->name('admin_menu_update_login');
        Route::post('/menu/store',[AdminMenuController::class,'store'])->name('admin_menu_store');
        Route::post('/menu/update/{id}',[AdminMenuController::class,'update'])->name('admin_menu_update');
        Route::post('/menu/delete/{id}',[AdminMenuController::class,'delete'])->name('admin_menu_delete');
    });
});