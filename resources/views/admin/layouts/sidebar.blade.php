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

        <div class="sidebar-section-label sidebar-label">Content</div>

        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
                <i class="fa-solid fa-house-chimney"></i>
                <span class="sidebar-label">Home Page</span>
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin_page_home_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/home/*') ? 'active' : '' }}">Home Page Settings</a>
                <a href="{{ route('admin_slider_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/slider/*') ? 'active' : '' }}">Hero Slider</a>
            </div>
        </div>

        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
                <i class="fa-solid fa-info-circle"></i>
                <span class="sidebar-label">About</span>
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin_about_item_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/about-item/*') ? 'active' : '' }}">About Content</a>
                <a href="{{ route('admin_page_about_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/about/*') ? 'active' : '' }}">About Page Settings</a>
            </div>
        </div>

        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
                <i class="fa-solid fa-calendar"></i>
                <span class="sidebar-label">Events</span>
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin_event_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/event/*') && !Request::is('admin/event-category/*') ? 'active' : '' }}">Events</a>
                <a href="{{ route('admin_event_category_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/event-category/*') ? 'active' : '' }}">Categories</a>
                <a href="{{ route('admin_page_event_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/event/*') ? 'active' : '' }}">Events Page Settings</a>
            </div>
        </div>

        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
                <i class="fa-solid fa-newspaper"></i>
                <span class="sidebar-label">Blog &amp; News</span>
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin_post_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/post/index') ? 'active' : '' }}">Posts</a>
                <a href="{{ route('admin_post_category_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/post-category/*') ? 'active' : '' }}">Categories</a>
                <a href="{{ route('admin_post_comments') }}" class="nav-link nav-sub-link {{ Request::is('admin/post/comments') ? 'active' : '' }}">Comments</a>
                <a href="{{ route('admin_post_replies') }}" class="nav-link nav-sub-link {{ Request::is('admin/post/rep*') ? 'active' : '' }}">Replies</a>
                <a href="{{ route('admin_page_blog_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/blog/*') ? 'active' : '' }}">Blog Page Settings</a>
            </div>
        </div>

        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
                <i class="fa-solid fa-file-pdf"></i>
                <span class="sidebar-label">Publications</span>
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin_publication_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/publication/*') && !Request::is('admin/publication-category/*') ? 'active' : '' }}">Publications</a>
                <a href="{{ route('admin_publication_category_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/publication-category/*') ? 'active' : '' }}">Categories</a>
            </div>
        </div>

        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
                <i class="fa-solid fa-users"></i>
                <span class="sidebar-label">Team</span>
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin_member_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/member/*') ? 'active' : '' }}">Team Members</a>
                <a href="{{ route('admin_page_team_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/team/*') ? 'active' : '' }}">Team Page Settings</a>
            </div>
        </div>

        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
                <i class="fa-solid fa-image"></i>
                <span class="sidebar-label">Gallery</span>
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin_photo_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/photo/*') ? 'active' : '' }}">Photos</a>
                <a href="{{ route('admin_page_gallery_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/gallery/*') ? 'active' : '' }}">Gallery Page Settings</a>
            </div>
        </div>

        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
                <i class="fa-solid fa-question"></i>
                <span class="sidebar-label">FAQ</span>
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin_faq_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/faq/*') ? 'active' : '' }}">FAQs</a>
                <a href="{{ route('admin_page_faq_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/faq/*') ? 'active' : '' }}">FAQ Page Settings</a>
            </div>
        </div>

        <div class="sidebar-section-label sidebar-label">Pages &amp; Navigation</div>

        <a href="{{ route('admin_menu_index') }}" class="nav-link {{ Request::is('admin/menu*') ? 'active' : '' }}">
            <i class="fa-solid fa-bars"></i>
            <span class="sidebar-label">Menu</span>
        </a>

        <a href="{{ route('admin_custom_page_index') }}" class="nav-link {{ Request::is('admin/custom-page/*') ? 'active' : '' }}">
            <i class="fa-solid fa-file"></i>
            <span class="sidebar-label">Custom Pages</span>
        </a>

        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
                <i class="fa-solid fa-envelope"></i>
                <span class="sidebar-label">Contact &amp; Donate</span>
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin_page_contact_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/contact/*') ? 'active' : '' }}">Contact Page Settings</a>
                <a href="{{ route('admin_page_donate_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/donate/*') ? 'active' : '' }}">Donate Page Settings</a>
            </div>
        </div>

        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
                <i class="fa-solid fa-scale-balanced"></i>
                <span class="sidebar-label">Legal Pages</span>
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin_page_terms_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/terms/*') ? 'active' : '' }}">Terms Page</a>
                <a href="{{ route('admin_page_privacy_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/privacy/*') ? 'active' : '' }}">Privacy Page</a>
            </div>
        </div>

        <div class="sidebar-section-label sidebar-label">Engagement</div>

        <a href="{{ route('admin_donation_index') }}" class="nav-link {{ Request::is('admin/donation/*') ? 'active' : '' }}">
            <i class="fa-solid fa-hand-holding-heart"></i>
            <span class="sidebar-label">Donations</span>
        </a>

        <a href="{{ route('admin_subscriber_index') }}" class="nav-link {{ Request::is('admin/subscriber/*') ? 'active' : '' }}">
            <i class="fa-solid fa-envelope-open-text"></i>
            <span class="sidebar-label">Newsletter Subscribers</span>
        </a>

        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
                <i class="fa-solid fa-user"></i>
                <span class="sidebar-label">Users &amp; Login</span>
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin_user_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/user/*') ? 'active' : '' }}">Registered Users</a>
                <a href="{{ route('admin_page_login_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/login/*') ? 'active' : '' }}">Login Page Settings</a>
                <a href="{{ route('admin_page_user_index') }}" class="nav-link nav-sub-link {{ Request::is('admin/page-item/user/*') ? 'active' : '' }}">User Panel Settings</a>
            </div>
        </div>

        <div class="sidebar-section-label sidebar-label">Site Settings</div>

        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
                <i class="fa-solid fa-gear"></i>
                <span class="sidebar-label">Settings</span>
                <i class="fa-solid fa-chevron-right nav-arrow sidebar-label"></i>
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin_setting_logo') }}" class="nav-link nav-sub-link {{ Request::is('admin/setting/logo*') ? 'active' : '' }}">Logo</a>
                <a href="{{ route('admin_setting_favicon') }}" class="nav-link nav-sub-link {{ Request::is('admin/setting/favicon*') ? 'active' : '' }}">Favicon</a>
                <a href="{{ route('admin_setting_banner') }}" class="nav-link nav-sub-link {{ Request::is('admin/setting/banner*') ? 'active' : '' }}">Banner</a>
                <a href="{{ route('admin_setting_top_bar') }}" class="nav-link nav-sub-link {{ Request::is('admin/setting/top-bar*') ? 'active' : '' }}">Top Bar</a>
                <a href="{{ route('admin_setting_footer') }}" class="nav-link nav-sub-link {{ Request::is('admin/setting/footer*') ? 'active' : '' }}">Footer</a>
                <a href="{{ route('admin_setting_theme_color') }}" class="nav-link nav-sub-link {{ Request::is('admin/setting/theme-color*') ? 'active' : '' }}">Theme Colors</a>
                <a href="{{ route('admin_setting_layout_direction') }}" class="nav-link nav-sub-link {{ Request::is('admin/setting/layout-direction*') ? 'active' : '' }}">Language Direction</a>
                <a href="{{ route('admin_setting_paypal') }}" class="nav-link nav-sub-link {{ Request::is('admin/setting/paypal*') ? 'active' : '' }}">PayPal</a>
            </div>
        </div>
    </nav>
</aside>
