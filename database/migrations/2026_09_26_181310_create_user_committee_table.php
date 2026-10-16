<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_committee', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'member_category_id']);
        });

        // Carry over the old single-committee text value (if any) into the new pivot,
        // matching it against the real committee categories by name.
        $rows = DB::table('users')->whereNotNull('committee')->where('committee', '!=', '')->get(['id', 'committee']);
        foreach ($rows as $row) {
            $category = DB::table('member_categories')->where('name', $row->committee)->first();
            if ($category) {
                DB::table('user_committee')->insertOrIgnore([
                    'user_id' => $row->id,
                    'member_category_id' => $category->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['committee', 'committee_sv']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('committee')->nullable();
            $table->string('committee_sv')->nullable();
        });
        Schema::dropIfExists('user_committee');
    }
};
