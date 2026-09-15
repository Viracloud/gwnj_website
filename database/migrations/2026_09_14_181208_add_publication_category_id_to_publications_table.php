<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->unsignedBigInteger('publication_category_id')->nullable()->after('id');
            $table->foreign('publication_category_id')->references('id')->on('publication_categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->dropForeign(['publication_category_id']);
            $table->dropColumn('publication_category_id');
        });
    }
};
