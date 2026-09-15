<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_sv')->nullable();
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->string('category_sv')->nullable();
            $table->text('description')->nullable();
            $table->text('description_sv')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('file')->nullable();
            $table->date('published_date')->nullable();
            $table->enum('status', ['Show', 'Hide'])->default('Show');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
