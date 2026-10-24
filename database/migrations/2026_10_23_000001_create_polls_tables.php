<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Polls & surveys. A poll either collects votes on this website ("site")
     * or shows the results of a Google Form ("google", read from a published
     * CSV of a sheet tab that contains only the survey columns).
     */
    public function up(): void
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_sv')->nullable();
            $table->string('slug')->unique();
            $table->string('slug_sv')->nullable();                 // kept for the shared slug helper
            $table->text('description')->nullable();
            $table->text('description_sv')->nullable();
            $table->string('source')->default('site');              // site | google
            $table->string('status')->default('draft');             // draft | open | closed
            $table->string('results_visibility')->default('after_vote'); // after_vote | after_close | always
            $table->timestamp('ends_at')->nullable();
            $table->boolean('show_on_home')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            // Google Form source
            $table->string('google_form_url', 1000)->nullable();
            $table->string('google_csv_url', 1000)->nullable();
            $table->longText('google_columns')->nullable();          // JSON: columns chosen for display
            $table->longText('google_cache')->nullable();            // JSON: aggregated results only
            $table->timestamp('google_synced_at')->nullable();
            $table->string('google_sync_error', 500)->nullable();
            $table->timestamps();
        });

        Schema::create('poll_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $table->string('question', 500);
            $table->string('question_sv', 500)->nullable();
            $table->string('type')->default('single');              // single | multiple | rating | text
            $table->boolean('required')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('poll_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_question_id')->constrained()->cascadeOnDelete();
            $table->string('label', 500);
            $table->string('label_sv', 500)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('poll_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $table->string('voter_token', 64);                      // random id kept in the voter's cookie
            $table->string('ip_hash', 64);                          // keyed hash, never the raw IP
            $table->timestamps();
            $table->unique(['poll_id', 'voter_token']);
            $table->index(['poll_id', 'ip_hash']);
        });

        Schema::create('poll_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_response_id')->constrained()->cascadeOnDelete();
            $table->foreignId('poll_question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('poll_option_id')->nullable()->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating')->nullable();
            $table->text('text')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poll_answers');
        Schema::dropIfExists('poll_responses');
        Schema::dropIfExists('poll_options');
        Schema::dropIfExists('poll_questions');
        Schema::dropIfExists('polls');
    }
};
