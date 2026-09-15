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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo_1')->nullable();
            $table->string('logo_2')->nullable();
            $table->string('favicon')->nullable();
            $table->string('banner')->nullable();
            $table->string('top_bar_email')->nullable();
            $table->string('top_bar_phone')->nullable();
            $table->string('top_bar_address')->nullable();
            $table->string('top_bar_facebook')->nullable();
            $table->string('top_bar_twitter')->nullable();
            $table->string('top_bar_linkedin')->nullable();
            $table->string('top_bar_instagram')->nullable();
            $table->string('footer_facebook')->nullable();
            $table->string('footer_twitter')->nullable();
            $table->string('footer_linkedin')->nullable();
            $table->string('footer_instagram')->nullable();
            $table->string('footer_phone')->nullable();
            $table->string('footer_email')->nullable();
            $table->string('footer_copyright')->nullable();
            $table->text('theme_color')->nullable();
            $table->text('layout_direction')->nullable();
            $table->string('paypal_mode')->default('sandbox')->nullable();
            $table->text('paypal_client_id')->nullable();
            $table->text('paypal_client_secret')->nullable();
            $table->string('paypal_currency')->default('USD')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
