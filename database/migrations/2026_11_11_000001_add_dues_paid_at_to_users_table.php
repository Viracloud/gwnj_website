<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // When the dues were last paid (online or marked by an admin). A card renewal needs a
            // payment made after the current card was issued, so one payment buys one year.
            $table->timestamp('dues_paid_at')->nullable()->after('dues_paid');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('dues_paid_at');
        });
    }
};
