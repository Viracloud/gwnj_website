<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_user_items', function (Blueprint $table) {
            $table->id();
            $table->string('dashboard_page_title')->nullable();
            $table->string('profile_page_title')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_meta_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_user_items');
    }
};
