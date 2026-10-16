<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_sv')->nullable();
            $table->string('slug')->unique();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('members', function (Blueprint $table) {
            $table->foreignId('member_category_id')->nullable()->after('id')
                ->constrained('member_categories')->nullOnDelete();
            $table->string('name_sv')->nullable()->after('name');
            $table->string('slug_sv')->nullable()->after('slug');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('show_contact')->default(false);
        });

        Schema::table('page_team_items', function (Blueprint $table) {
            $table->text('intro')->nullable();
            $table->text('intro_sv')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('page_team_items', function (Blueprint $table) {
            $table->dropColumn(['intro', 'intro_sv']);
        });
        Schema::table('members', function (Blueprint $table) {
            $table->dropConstrainedForeignId('member_category_id');
            $table->dropColumn(['name_sv', 'slug_sv', 'sort_order', 'show_contact']);
        });
        Schema::dropIfExists('member_categories');
    }
};
