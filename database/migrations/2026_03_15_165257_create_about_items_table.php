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
        Schema::create('about_items', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('heading');
            $table->longText('text');
            $table->string('subheading')->nullable();
            $table->string('experience_year')->nullable();
            $table->string('experience_text')->nullable();
            $table->string('youtube_id')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('phone_text')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_items');
    }
};
