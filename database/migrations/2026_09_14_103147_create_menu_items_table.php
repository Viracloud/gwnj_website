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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('label');
            $table->string('label_sv')->nullable();
            $table->enum('link_type', ['route', 'custom_page', 'url'])->default('url');
            $table->string('route_name')->nullable();
            $table->unsignedBigInteger('custom_page_id')->nullable();
            $table->string('url')->nullable();
            $table->boolean('open_new_tab')->default(false);
            $table->boolean('is_button')->default(false);
            $table->integer('order')->default(0);
            $table->enum('status', ['Show', 'Hide'])->default('Show');
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('menu_items')->cascadeOnDelete();
            $table->foreign('custom_page_id')->references('id')->on('custom_pages')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
