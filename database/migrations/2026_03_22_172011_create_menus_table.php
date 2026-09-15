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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->text('menu_home_name')->nullable();
            $table->text('menu_home_status')->nullable();
            $table->text('menu_about_name')->nullable();
            $table->text('menu_about_status')->nullable();
            $table->text('menu_service_name')->nullable();
            $table->text('menu_service_status')->nullable();
            $table->text('menu_event_name')->nullable();
            $table->text('menu_event_status')->nullable();
            $table->text('menu_team_member_name')->nullable();
            $table->text('menu_team_member_status')->nullable();
            $table->text('menu_project_name')->nullable();
            $table->text('menu_project_status')->nullable();
            $table->text('menu_gallery_name')->nullable();
            $table->text('menu_gallery_status')->nullable();
            $table->text('menu_faq_name')->nullable();
            $table->text('menu_faq_status')->nullable();
            $table->text('menu_blog_name')->nullable();
            $table->text('menu_blog_status')->nullable();
            $table->text('menu_contact_name')->nullable();
            $table->text('menu_contact_status')->nullable();
            $table->text('menu_donate_name')->nullable();
            $table->text('menu_donate_status')->nullable();
            $table->text('menu_login_name')->nullable();
            $table->text('menu_login_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
