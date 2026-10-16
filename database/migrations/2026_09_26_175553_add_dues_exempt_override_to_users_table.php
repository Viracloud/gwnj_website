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
        Schema::table('users', function (Blueprint $table) {
            // null = auto-detect from country (Iran/Afghanistan/Pakistan are exempt),
            // true = force exempt, false = force dues required, regardless of country.
            $table->boolean('dues_exempt_override')->nullable()->after('dues_paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('dues_exempt_override');
        });
    }
};
