@php
    $me = auth('admin')->user();
    // Items waiting for someone to act on them, shown as small badges in the menu.
    $pending_comments = \App\Models\Comment::where('status', 'Pending')->count()
        + \App\Models\Reply::where('status', 'Pending')->count();
    $pending_applications = $me->isSuperAdmin() ? \App\Models\User::where('membership_status', 'pending')->count() : 0;
    $pending_cards = $me->isSuperAdmin() ? \App\Models\User::where('card_status', 'requested')->count() : 0;
@endphp
<aside id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo"><img src="{{ asset('uploads/'.$global_setting->logo_1) }}" alt="GWJN" style="width:100%;height:100%;object-fit:contain"></div>
        <span class="sidebar-brand sidebar-label">GWJN Admin</span>
    </div>
    <nav class="sidebar-nav space-y-1">
        <a href="{{ route('admin_dashboard') }}" class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i>
            <span class="sidebar-label">Dashboard</span>
        </a>

        {{-- ================= 1. Everyday content ================= --}}
        <div class="sidebar-section-label sidebar-label">Publish content</div>

        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
                <i class="fa-solid fa-newspaper"></i>
                <span class="sidebar-label">News &amp; Posts</span>
                @if($pending_comments)<span class="nav-badge sidebar-label">{{ $pending_comments }}</span>@endif
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin_post_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/post/index') ? 'active' : '' }}">All Posts</a>
                <a href="{{ route('admin_post_category_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/post-category/*') ? 'active' : '' }}">Post Categories</a>
                <a href="{{ route('admin_post_comments') }}" class="nav-link nav-sub-link {{ Request::is('admin/post/comments') ? 'active' : '' }}">Comments</a>
                <a href="{{ route('admin_post_replies') }}" class="nav-link nav-sub-link {{ Request::is('admin/post/rep*') ? 'active' : '' }}">Comment Replies</a>
            </div>
        </div>

        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
                <i class="fa-solid fa-calendar-days"></i>
                <span class="sidebar-label">Events</span>
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin_event_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/event/*') && !Request::is('admin/event-category/*') ? 'active' : '' }}">All Events</a>
                <a href="{{ route('admin_event_category_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/event-category/*') ? 'active' : '' }}">Event Categories</a>
            </div>
        </div>

        <a href="{{ route('admin_campaign_index') }}" class="nav-link {{ Request::is('admin/campaign/*') ? 'active' : '' }}">
            <i class="fa-solid fa-bullhorn"></i>
            <span class="sidebar-label">Campaigns</span>
        </a>

        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
                <i class="fa-solid fa-book-open"></i>
                <span class="sidebar-label">Publications</span>
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin_publication_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/publication/*') && !Request::is('admin/publication-category/*') ? 'active' : '' }}">All Publications</a>
                <a href="{{ route('admin_publication_category_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/publication-category/*') ? 'active' : '' }}">Publication Categories</a>
            </div>
        </div>

        <a href="{{ route('admin_photo_index') }}" class="nav-link {{ Request::is('admin/photo/*') ? 'active' : '' }}">
            <i class="fa-solid fa-images"></i>
            <span class="sidebar-label">Photo Gallery</span>
        </a>

        {{-- ================= 2. Organisation ================= --}}
        <div class="sidebar-section-label sidebar-label">Organisation</div>

        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
                <i class="fa-solid fa-people-group"></i>
                <span class="sidebar-label">Leadership Team</span>
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin_member_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/member/*') ? 'active' : '' }}">People</a>
                <a href="{{ route('admin_member_category_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/member-category/*') ? 'active' : '' }}">Groups</a>
            </div>
        </div>

        <a href="{{ route('admin_partner_index') }}" class="nav-link {{ Request::is('admin/partner/*') ? 'active' : '' }}">
            <i class="fa-solid fa-handshake"></i>
            <span class="sidebar-label">Partners &amp; Logos</span>
        </a>

        <a href="{{ route('admin_faq_index') }}" class="nav-link {{ Request::is('admin/faq/*') ? 'active' : '' }}">
            <i class="fa-solid fa-circle-question"></i>
            <span class="sidebar-label">FAQs</span>
        </a>

        @if($me->isSuperAdmin())
        {{-- ================= 3. People & money (super admin) ================= --}}
        <div class="sidebar-section-label sidebar-label">Members &amp; Support</div>

        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
                <i class="fa-solid fa-id-card"></i>
                <span class="sidebar-label">Membership</span>
                @if($pending_applications + $pending_cards)<span class="nav-badge sidebar-label">{{ $pending_applications + $pending_cards }}</span>@endif
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin_membership_applications') }}" class="nav-link nav-sub-link {{ Request::is('admin/membership/applications*') ? 'active' : '' }}">New Applications @if($pending_applications)<span class="nav-badge">{{ $pending_applications }}</span>@endif</a>
                <a href="{{ route('admin_membership_members') }}" class="nav-link nav-sub-link {{ Request::is('admin/membership/members*') ? 'active' : '' }}">Members</a>
                <a href="{{ route('admin_membership_card_requests') }}" class="nav-link nav-sub-link {{ Request::is('admin/membership/card-requests*') ? 'active' : '' }}">Card Requests @if($pending_cards)<span class="nav-badge">{{ $pending_cards }}</span>@endif</a>
                <a href="{{ route('admin_membership_invitations') }}" class="nav-link nav-sub-link {{ Request::is('admin/membership/invitations*') ? 'active' : '' }}">Invitations</a>
                <a href="{{ route('admin_user_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/user/*') ? 'active' : '' }}">All Registered Users</a>
            </div>
        </div>

        <a href="{{ route('admin_donation_index') }}" class="nav-link {{ Request::is('admin/donation/*') ? 'active' : '' }}">
            <i class="fa-solid fa-hand-holding-heart"></i>
            <span class="sidebar-label">Donations</span>
        </a>

        <a href="{{ route('admin_subscriber_index') }}" class="nav-link {{ Request::is('admin/subscriber/*') ? 'active' : '' }}">
            <i class="fa-solid fa-envelope-open-text"></i>
            <span class="sidebar-label">Newsletter Subscribers</span>
        </a>
        @endif

        {{-- ================= 4. Website pages & layout ================= --}}
        <div class="sidebar-section-label sidebar-label">Website Pages</div>

        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
                <i class="fa-solid fa-house-chimney"></i>
                <span class="sidebar-label">Home Page</span>
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin_slider_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/slider/*') ? 'active' : '' }}">Cover Image &amp; Headline</a>
                <a href="{{ route('admin_home_text_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/home-text/*') ? 'active' : '' }}">Texts &amp; Sections On/Off</a>
                <a href="{{ route('admin_page_home_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/home/*') ? 'active' : '' }}">Section Settings</a>
            </div>
        </div>

        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
                <i class="fa-solid fa-file-lines"></i>
                <span class="sidebar-label">Pages</span>
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin_custom_page_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/custom-page/index') || Request::is('admin/custom-page/*/gallery') ? 'active' : '' }}">All Pages</a>
                <a href="{{ route('admin_custom_page_about_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/custom-page/about-index') ? 'active' : '' }}">Pages under “About”</a>
                <a href="{{ route('admin_custom_page_our_work_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/custom-page/our-work-index') ? 'active' : '' }}">Pages under “Our Work”</a>
                <a href="{{ route('admin_custom_page_news_media_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/custom-page/news-media-index') ? 'active' : '' }}">Pages under “News &amp; Media”</a>
                <a href="{{ route('admin_custom_page_get_involved_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/custom-page/get-involved-index') ? 'active' : '' }}">Pages under “Get Involved”</a>
                <a href="{{ route('admin_custom_page_partnerships_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/custom-page/partnerships-index') ? 'active' : '' }}">Pages under “Partnerships”</a>
                <a href="{{ route('admin_custom_page_contact_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/custom-page/contact-index') ? 'active' : '' }}">Pages under “Contact”</a>
            </div>
        </div>

        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
                <i class="fa-solid fa-heading"></i>
                <span class="sidebar-label">Page Titles &amp; Texts</span>
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin_about_item_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/about-item/*') ? 'active' : '' }}">About – Main Content</a>
                <a href="{{ route('admin_page_about_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/about/*') ? 'active' : '' }}">About Page</a>
                <a href="{{ route('admin_page_team_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/team/*') ? 'active' : '' }}">Leadership Page</a>
                <a href="{{ route('admin_page_event_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/event/*') ? 'active' : '' }}">Events Page</a>
                <a href="{{ route('admin_page_blog_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/blog/*') ? 'active' : '' }}">News / Blog Page</a>
                <a href="{{ route('admin_page_gallery_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/gallery/*') ? 'active' : '' }}">Gallery Page</a>
                <a href="{{ route('admin_page_faq_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/faq/*') ? 'active' : '' }}">FAQ Page</a>
                <a href="{{ route('admin_page_contact_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/contact/*') ? 'active' : '' }}">Contact Page</a>
                <a href="{{ route('admin_page_donate_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/donate/*') ? 'active' : '' }}">Donate Page</a>
                <a href="{{ route('admin_page_login_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/login/*') ? 'active' : '' }}">Login Page</a>
                <a href="{{ route('admin_page_user_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/user/*') ? 'active' : '' }}">Member Panel</a>
                <a href="{{ route('admin_page_terms_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/terms/*') ? 'active' : '' }}">Terms of Use</a>
                <a href="{{ route('admin_page_privacy_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/privacy/*') ? 'active' : '' }}">Privacy Policy</a>
            </div>
        </div>

        @if($me->isSuperAdmin())
        <a href="{{ route('admin_menu_index') }}" class="nav-link {{ Request::is('admin/menu*') ? 'active' : '' }}">
            <i class="fa-solid fa-bars"></i>
            <span class="sidebar-label">Main Menu</span>
        </a>

        {{-- ================= 5. Settings (super admin) ================= --}}
        <div class="sidebar-section-label sidebar-label">Settings</div>

        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
                <i class="fa-solid fa-palette"></i>
                <span class="sidebar-label">Look &amp; Branding</span>
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin_setting_logo') }}" class="nav-link nav-sub-link {{ Request::is('admin/setting/logo*') ? 'active' : '' }}">Logo</a>
                <a href="{{ route('admin_setting_favicon') }}" class="nav-link nav-sub-link {{ Request::is('admin/setting/favicon*') ? 'active' : '' }}">Browser Icon (Favicon)</a>
                <a href="{{ route('admin_setting_theme_color') }}" class="nav-link nav-sub-link {{ Request::is('admin/setting/theme-color*') ? 'active' : '' }}">Colours</a>
                <a href="{{ route('admin_setting_banner') }}" class="nav-link nav-sub-link {{ Request::is('admin/setting/banner*') ? 'active' : '' }}">Inner Page Cover Image</a>
                <a href="{{ route('admin_setting_top_bar') }}" class="nav-link nav-sub-link {{ Request::is('admin/setting/top-bar*') ? 'active' : '' }}">Top Bar</a>
                <a href="{{ route('admin_setting_footer') }}" class="nav-link nav-sub-link {{ Request::is('admin/setting/footer*') ? 'active' : '' }}">Footer</a>
                <a href="{{ route('admin_setting_layout_direction') }}" class="nav-link nav-sub-link {{ Request::is('admin/setting/layout-direction*') ? 'active' : '' }}">Default Language</a>
            </div>
        </div>

        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
                <i class="fa-solid fa-sliders"></i>
                <span class="sidebar-label">Technical</span>
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin_setting_paypal') }}" class="nav-link nav-sub-link {{ Request::is('admin/setting/paypal*') ? 'active' : '' }}">Online Payments (PayPal)</a>
                <a href="{{ route('admin_setting_seo') }}" class="nav-link nav-sub-link {{ Request::is('admin/setting/seo*') ? 'active' : '' }}">Google &amp; SEO</a>
                <a href="{{ route('admin_setting_sitemap') }}" class="nav-link nav-sub-link {{ Request::is('admin/setting/sitemap*') ? 'active' : '' }}">Sitemap</a>
            </div>
        </div>

        <a href="{{ route('admin_account_index') }}" class="nav-link {{ Request::is('admin/admin-user/*') ? 'active' : '' }}">
            <i class="fa-solid fa-user-shield"></i>
            <span class="sidebar-label">Admin Accounts</span>
        </a>
        @endif
    </nav>
</aside>
