<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'page_home_items', 'page_about_items', 'page_event_items', 'page_blog_items',
        'page_team_items', 'page_gallery_items', 'page_faq_items', 'page_login_items',
        'page_terms_items', 'page_privacy_items', 'page_contact_items', 'page_donate_items',
        'page_user_items',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->string('seo_title_sv')->nullable();
                $t->text('seo_meta_description_sv')->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn(['seo_title_sv', 'seo_meta_description_sv']);
            });
        }
    }
};
