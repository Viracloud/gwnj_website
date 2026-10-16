<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('membership_status')->default('none')->after('zip');
            $table->string('membership_type')->nullable()->after('membership_status');
            $table->string('membership_source')->nullable()->after('membership_type');
            $table->string('membership_id')->nullable()->unique()->after('membership_source');
            $table->timestamp('membership_applied_at')->nullable()->after('membership_id');
            $table->timestamp('membership_approved_at')->nullable()->after('membership_applied_at');
            $table->text('membership_rejected_reason')->nullable()->after('membership_approved_at');
            $table->boolean('dues_paid')->default(false)->after('membership_rejected_reason');
            $table->string('card_status')->default('not_requested')->after('dues_paid');
            $table->timestamp('card_requested_at')->nullable()->after('card_status');
            $table->timestamp('card_issued_at')->nullable()->after('card_requested_at');
            $table->string('verification_token')->nullable()->unique()->after('card_issued_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'membership_status', 'membership_type', 'membership_source', 'membership_id',
                'membership_applied_at', 'membership_approved_at', 'membership_rejected_reason',
                'dues_paid', 'card_status', 'card_requested_at', 'card_issued_at', 'verification_token',
            ]);
        });
    }
};
