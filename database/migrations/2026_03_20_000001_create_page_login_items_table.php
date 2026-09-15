<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_login_items', function (Blueprint $table) {
            $table->id();
            $table->string('login_page_title')->nullable();
            $table->string('register_page_title')->nullable();
            $table->string('forget_password_page_title')->nullable();
            $table->string('reset_password_page_title')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_meta_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_login_items');
    }
};
