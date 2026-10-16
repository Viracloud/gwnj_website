<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_pages', function (Blueprint $table) {
            $table->text('excerpt')->nullable()->after('title_sv');
            $table->text('excerpt_sv')->nullable()->after('excerpt');
        });

        Schema::create('custom_page_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_page_id')->constrained()->cascadeOnDelete();
            $table->string('image');
            $table->string('caption')->nullable();
            $table->string('caption_sv')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_page_images');
        Schema::table('custom_pages', function (Blueprint $table) {
            $table->dropColumn(['excerpt', 'excerpt_sv']);
        });
    }
};
