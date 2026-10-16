<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('sitemap_include_static')->default(true);
            $table->boolean('sitemap_include_pages')->default(true);
            $table->boolean('sitemap_include_events')->default(true);
            $table->boolean('sitemap_include_posts')->default(true);
            $table->boolean('sitemap_include_campaigns')->default(true);
            $table->boolean('sitemap_include_members')->default(true);
            $table->string('sitemap_priority_static')->default('0.7');
            $table->string('sitemap_priority_pages')->default('0.5');
            $table->string('sitemap_priority_events')->default('0.6');
            $table->string('sitemap_priority_posts')->default('0.6');
            $table->string('sitemap_priority_campaigns')->default('0.6');
            $table->string('sitemap_priority_members')->default('0.5');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'sitemap_include_static', 'sitemap_include_pages', 'sitemap_include_events',
                'sitemap_include_posts', 'sitemap_include_campaigns', 'sitemap_include_members',
                'sitemap_priority_static', 'sitemap_priority_pages', 'sitemap_priority_events',
                'sitemap_priority_posts', 'sitemap_priority_campaigns', 'sitemap_priority_members',
            ]);
        });
    }
};
