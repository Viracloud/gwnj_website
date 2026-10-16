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
            $table->string('occupation')->nullable()->after('position_sv');
            $table->string('occupation_sv')->nullable()->after('occupation');
            $table->string('committee')->nullable()->after('occupation_sv');
            $table->string('committee_sv')->nullable()->after('committee');
            $table->timestamp('card_expires_at')->nullable()->after('card_issued_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['occupation', 'occupation_sv', 'committee', 'committee_sv', 'card_expires_at']);
        });
    }
};
