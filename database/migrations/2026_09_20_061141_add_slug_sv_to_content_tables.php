<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'posts' => 'title_sv',
        'events' => 'title_sv',
        'post_categories' => 'name_sv',
        'custom_pages' => 'title_sv',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table => $source) {
            Schema::table($table, function (Blueprint $t) {
                $t->string('slug_sv')->nullable()->after('slug');
            });

            $used = [];
            foreach (DB::table($table)->get() as $row) {
                $slug = persian_slug($row->{$source} ?? '');
                if ($slug === '') {
                    continue;
                }
                $base = $slug;
                $i = 2;
                while (isset($used[$slug]) || DB::table($table)->where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $used[$slug] = true;
                DB::table($table)->where('id', $row->id)->update(['slug_sv' => $slug]);
            }
        }
    }

    public function down(): void
    {
        foreach (array_keys($this->tables) as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn('slug_sv');
            });
        }
    }
};
