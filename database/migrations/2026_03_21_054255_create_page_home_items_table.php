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
        Schema::create('page_home_items', function (Blueprint $table) {
            $table->id();
            $table->text('about_status')->nullable();

            $table->text('service_subheading')->nullable();
            $table->text('service_heading')->nullable();
            $table->text('service_text')->nullable();
            $table->text('service_status')->nullable();

            $table->text('project_subheading')->nullable();
            $table->text('project_heading')->nullable();
            $table->text('project_text')->nullable();
            $table->text('project_status')->nullable();

            $table->text('testimonial_subheading')->nullable();
            $table->text('testimonial_heading')->nullable();
            $table->text('testimonial_status')->nullable();

            $table->text('team_subheading')->nullable();
            $table->text('team_heading')->nullable();
            $table->text('team_status')->nullable();

            $table->text('event_subheading')->nullable();
            $table->text('event_heading')->nullable();
            $table->text('event_status')->nullable();

            $table->text('blog_subheading')->nullable();
            $table->text('blog_heading')->nullable();
            $table->text('blog_status')->nullable();

            $table->text('seo_title')->nullable();
            $table->text('seo_meta_description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_home_items');
    }
};
