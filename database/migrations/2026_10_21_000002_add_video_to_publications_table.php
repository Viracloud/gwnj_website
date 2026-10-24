<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Reports and documentation can come with a video: either a link
     * (YouTube / Vimeo / Facebook ...) or an uploaded video file.
     */
    public function up(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->string('video_url', 500)->nullable()->after('file');
            $table->string('video_file')->nullable()->after('video_url');
        });
    }

    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->dropColumn(['video_url', 'video_file']);
        });
    }
};
