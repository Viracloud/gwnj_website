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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('theme_ink')->default('#000000')->after('theme_color');
            $table->string('theme_signal')->default('#C94B18')->after('theme_ink');
            $table->string('theme_hope')->default('#2E8B57')->after('theme_signal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['theme_ink', 'theme_signal', 'theme_hope']);
        });
    }
};
