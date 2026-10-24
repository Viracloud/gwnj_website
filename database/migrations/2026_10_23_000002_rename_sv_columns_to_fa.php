<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The Dari translation columns were named "*_sv" (a leftover from the original
     * template — "sv" is the code for Swedish). Rename them to "*_fa" (Dari).
     * CHANGE COLUMN is used because it works on every MySQL / MariaDB version.
     */
    private function columns(): array
    {
        return [
            ['about_items', 'heading_sv', 'varchar(255)'],
            ['about_items', 'subheading_sv', 'varchar(255)'],
            ['about_items', 'experience_text_sv', 'varchar(255)'],
            ['about_items', 'phone_text_sv', 'varchar(255)'],
            ['about_items', 'button_text_sv', 'varchar(255)'],
            ['about_items', 'text_sv', 'longtext'],
            ['campaigns', 'title_sv', 'varchar(255)'],
            ['campaigns', 'slug_sv', 'varchar(255)'],
            ['campaigns', 'excerpt_sv', 'varchar(255)'],
            ['campaigns', 'content_sv', 'longtext'],
            ['campaigns', 'goal_sv', 'varchar(255)'],
            ['campaigns', 'cta_text_sv', 'varchar(255)'],
            ['campaigns', 'stat_label_sv', 'varchar(255)'],
            ['campaigns', 'countries_sv', 'varchar(255)'],
            ['campaigns', 'seo_title_sv', 'varchar(255)'],
            ['campaigns', 'seo_meta_description_sv', 'varchar(255)'],
            ['campaign_categories', 'name_sv', 'varchar(255)'],
            ['custom_pages', 'slug_sv', 'varchar(255)'],
            ['custom_pages', 'title_sv', 'varchar(255)'],
            ['custom_pages', 'excerpt_sv', 'text'],
            ['custom_pages', 'seo_title_sv', 'varchar(255)'],
            ['custom_pages', 'seo_meta_description_sv', 'varchar(255)'],
            ['custom_pages', 'content_sv', 'longtext'],
            ['custom_page_images', 'caption_sv', 'varchar(255)'],
            ['events', 'slug_sv', 'varchar(255)'],
            ['events', 'title_sv', 'varchar(255)'],
            ['events', 'event_location_sv', 'varchar(255)'],
            ['events', 'event_location_detail_sv', 'varchar(255)'],
            ['events', 'button_text_sv', 'varchar(255)'],
            ['events', 'description_sv', 'longtext'],
            ['event_categories', 'name_sv', 'varchar(255)'],
            ['event_faqs', 'question_sv', 'longtext'],
            ['event_faqs', 'answer_sv', 'longtext'],
            ['faqs', 'question_sv', 'varchar(255)'],
            ['faqs', 'answer_sv', 'longtext'],
            ['home_texts', 'value_sv', 'text'],
            ['members', 'name_sv', 'varchar(255)'],
            ['members', 'slug_sv', 'varchar(255)'],
            ['members', 'designation_sv', 'varchar(255)'],
            ['members', 'short_description_sv', 'varchar(255)'],
            ['members', 'description_sv', 'longtext'],
            ['member_awards', 'name_sv', 'varchar(255)'],
            ['member_categories', 'name_sv', 'varchar(255)'],
            ['menus', 'menu_home_name_sv', 'varchar(255)'],
            ['menus', 'menu_about_name_sv', 'varchar(255)'],
            ['menus', 'menu_event_name_sv', 'varchar(255)'],
            ['menus', 'menu_team_member_name_sv', 'varchar(255)'],
            ['menus', 'menu_gallery_name_sv', 'varchar(255)'],
            ['menus', 'menu_faq_name_sv', 'varchar(255)'],
            ['menus', 'menu_blog_name_sv', 'varchar(255)'],
            ['menus', 'menu_contact_name_sv', 'varchar(255)'],
            ['menus', 'menu_login_name_sv', 'varchar(255)'],
            ['menus', 'menu_donate_name_sv', 'varchar(255)'],
            ['menu_items', 'label_sv', 'varchar(255)'],
            ['page_about_items', 'seo_title_sv', 'varchar(255)'],
            ['page_about_items', 'seo_meta_description_sv', 'text'],
            ['page_blog_items', 'page_title_sv', 'varchar(255)'],
            ['page_blog_items', 'seo_title_sv', 'varchar(255)'],
            ['page_blog_items', 'seo_meta_description_sv', 'text'],
            ['page_contact_items', 'page_title_sv', 'varchar(255)'],
            ['page_contact_items', 'location_sv', 'varchar(255)'],
            ['page_contact_items', 'seo_title_sv', 'varchar(255)'],
            ['page_contact_items', 'seo_meta_description_sv', 'text'],
            ['page_donate_items', 'page_title_sv', 'varchar(255)'],
            ['page_donate_items', 'heading_sv', 'varchar(255)'],
            ['page_donate_items', 'description_sv', 'longtext'],
            ['page_donate_items', 'seo_title_sv', 'varchar(255)'],
            ['page_donate_items', 'seo_meta_description_sv', 'text'],
            ['page_event_items', 'page_title_sv', 'varchar(255)'],
            ['page_event_items', 'seo_title_sv', 'varchar(255)'],
            ['page_event_items', 'seo_meta_description_sv', 'text'],
            ['page_faq_items', 'page_title_sv', 'varchar(255)'],
            ['page_faq_items', 'seo_title_sv', 'varchar(255)'],
            ['page_faq_items', 'seo_meta_description_sv', 'text'],
            ['page_gallery_items', 'page_title_sv', 'varchar(255)'],
            ['page_gallery_items', 'seo_title_sv', 'varchar(255)'],
            ['page_gallery_items', 'seo_meta_description_sv', 'text'],
            ['page_home_items', 'team_subheading_sv', 'varchar(255)'],
            ['page_home_items', 'team_heading_sv', 'varchar(255)'],
            ['page_home_items', 'event_subheading_sv', 'varchar(255)'],
            ['page_home_items', 'event_heading_sv', 'varchar(255)'],
            ['page_home_items', 'blog_subheading_sv', 'varchar(255)'],
            ['page_home_items', 'blog_heading_sv', 'varchar(255)'],
            ['page_home_items', 'seo_title_sv', 'varchar(255)'],
            ['page_home_items', 'seo_meta_description_sv', 'text'],
            ['page_login_items', 'login_page_title_sv', 'varchar(255)'],
            ['page_login_items', 'register_page_title_sv', 'varchar(255)'],
            ['page_login_items', 'forget_password_page_title_sv', 'varchar(255)'],
            ['page_login_items', 'reset_password_page_title_sv', 'varchar(255)'],
            ['page_login_items', 'seo_title_sv', 'varchar(255)'],
            ['page_login_items', 'seo_meta_description_sv', 'text'],
            ['page_privacy_items', 'page_title_sv', 'varchar(255)'],
            ['page_privacy_items', 'page_content_sv', 'longtext'],
            ['page_privacy_items', 'seo_title_sv', 'varchar(255)'],
            ['page_privacy_items', 'seo_meta_description_sv', 'text'],
            ['page_team_items', 'page_title_sv', 'varchar(255)'],
            ['page_team_items', 'seo_title_sv', 'varchar(255)'],
            ['page_team_items', 'seo_meta_description_sv', 'text'],
            ['page_team_items', 'intro_sv', 'text'],
            ['page_terms_items', 'page_title_sv', 'varchar(255)'],
            ['page_terms_items', 'page_content_sv', 'longtext'],
            ['page_terms_items', 'seo_title_sv', 'varchar(255)'],
            ['page_terms_items', 'seo_meta_description_sv', 'text'],
            ['page_user_items', 'dashboard_page_title_sv', 'varchar(255)'],
            ['page_user_items', 'profile_page_title_sv', 'varchar(255)'],
            ['page_user_items', 'seo_title_sv', 'varchar(255)'],
            ['page_user_items', 'seo_meta_description_sv', 'text'],
            ['partners', 'name_sv', 'varchar(255)'],
            ['photos', 'caption_sv', 'varchar(255)'],
            ['polls', 'title_sv', 'varchar(255)'],
            ['polls', 'slug_sv', 'varchar(255)'],
            ['polls', 'description_sv', 'text'],
            ['poll_options', 'label_sv', 'varchar(500)'],
            ['poll_questions', 'question_sv', 'varchar(500)'],
            ['posts', 'slug_sv', 'varchar(255)'],
            ['posts', 'title_sv', 'varchar(255)'],
            ['posts', 'excerpt_sv', 'text'],
            ['posts', 'description_sv', 'longtext'],
            ['posts', 'seo_title_sv', 'varchar(255)'],
            ['posts', 'seo_meta_description_sv', 'varchar(300)'],
            ['post_categories', 'slug_sv', 'varchar(255)'],
            ['post_categories', 'name_sv', 'varchar(255)'],
            ['post_categories', 'menu_label_sv', 'varchar(255)'],
            ['publications', 'title_sv', 'varchar(255)'],
            ['publications', 'description_sv', 'text'],
            ['publication_categories', 'name_sv', 'varchar(255)'],
            ['settings', 'footer_about_text_sv', 'text'],
            ['settings', 'top_bar_address_sv', 'varchar(255)'],
            ['settings', 'footer_copyright_sv', 'varchar(255)'],
            ['settings', 'seo_default_description_sv', 'text'],
            ['sliders', 'heading_sv', 'varchar(255)'],
            ['sliders', 'text_sv', 'varchar(255)'],
            ['sliders', 'button_text_sv', 'varchar(255)'],
            ['users', 'position_sv', 'varchar(255)'],
            ['users', 'occupation_sv', 'varchar(255)'],
        ];
    }

    public function up(): void
    {
        foreach ($this->columns() as [$table, $column, $type]) {
            $new = substr($column, 0, -3).'_fa';
            if (Schema::hasColumn($table, $column) && !Schema::hasColumn($table, $new)) {
                DB::statement("ALTER TABLE `$table` CHANGE `$column` `$new` $type NULL DEFAULT NULL");
            }
        }
    }

    public function down(): void
    {
        foreach ($this->columns() as [$table, $column, $type]) {
            $new = substr($column, 0, -3).'_fa';
            if (Schema::hasColumn($table, $new) && !Schema::hasColumn($table, $column)) {
                DB::statement("ALTER TABLE `$table` CHANGE `$new` `$column` $type NULL DEFAULT NULL");
            }
        }
    }
};
