<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_contact_items', function (Blueprint $table) {
            $table->id();
            $table->string('page_title')->nullable();
            $table->text('phone_number')->nullable();
            $table->text('email_address')->nullable();
            $table->text('location')->nullable();
            $table->text('map_iframe_code')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_meta_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_contact_items');
    }
};
