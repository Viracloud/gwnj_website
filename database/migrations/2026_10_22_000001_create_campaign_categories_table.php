<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Categories for campaigns, like the ones posts, events and publications already have. */
    public function up(): void
    {
        Schema::create('campaign_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_sv')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->unsignedBigInteger('campaign_category_id')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('campaign_category_id');
        });
        Schema::dropIfExists('campaign_categories');
    }
};
