<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('title');
            $table->string('title_sv')->nullable();
            $table->string('slug')->unique();
            $table->string('slug_sv')->nullable();
            $table->string('excerpt')->nullable();
            $table->string('excerpt_sv')->nullable();
            $table->longText('content')->nullable();
            $table->longText('content_sv')->nullable();
            // active = currently running, won = achieved its goal, closed = ended without a win
            $table->enum('status', ['active', 'won', 'closed'])->default('active');
            $table->string('goal')->nullable();
            $table->string('goal_sv')->nullable();
            $table->string('cta_text')->nullable();
            $table->string('cta_text_sv')->nullable();
            $table->string('cta_link')->nullable();
            $table->string('stat_number')->nullable();
            $table->string('stat_label')->nullable();
            $table->string('stat_label_sv')->nullable();
            $table->string('countries')->nullable();
            $table->string('countries_sv')->nullable();
            $table->date('published_date')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('show_on_home')->default(false);
            $table->string('seo_title')->nullable();
            $table->string('seo_title_sv')->nullable();
            $table->string('seo_meta_description')->nullable();
            $table->string('seo_meta_description_sv')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
