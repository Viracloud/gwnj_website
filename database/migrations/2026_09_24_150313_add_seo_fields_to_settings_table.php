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
            $table->string('seo_title_template')->nullable();
            $table->text('seo_default_description')->nullable();
            $table->text('seo_default_description_sv')->nullable();
            $table->string('seo_og_image')->nullable();
            $table->boolean('seo_robots_indexable')->default(false);
            $table->string('seo_google_verification')->nullable();
            $table->string('seo_analytics_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'seo_title_template', 'seo_default_description', 'seo_default_description_sv',
                'seo_og_image', 'seo_robots_indexable', 'seo_google_verification', 'seo_analytics_id',
            ]);
        });
    }
};
