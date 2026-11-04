<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Website languages managed from the admin panel (Settings → Languages) instead of .env.
 * The table starts with what .env had: SITE_LOCALES (published), SITE_LOCALES_PREPARE
 * (being prepared) and the default language.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name', 60);
            $table->string('native', 60);
            $table->string('dir', 3)->default('ltr');
            $table->string('status', 10)->default('off'); // published | prepare | off
            $table->boolean('is_default')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $enabled = config('locales.enabled', ['en']);
        $prepare = config('locales.prepare', []);
        $default = default_locale();
        $order = 0;
        foreach (config('locales.supported') as $code => $meta) {
            DB::table('site_languages')->insert([
                'code' => $code,
                'name' => $meta['name'],
                'native' => $meta['native'],
                'dir' => $meta['dir'],
                'status' => in_array($code, $enabled, true) ? 'published' : (in_array($code, $prepare, true) ? 'prepare' : 'off'),
                'is_default' => $code === $default,
                'sort_order' => $order++,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('site_languages');
    }
};
