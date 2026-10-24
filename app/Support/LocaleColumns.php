<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Translation columns. Every translatable field has the English text in "<field>"
 * and one column per other language: "<field>_fa", "<field>_fr", "<field>_de" ...
 * The Dari (_fa) columns define which fields are translatable.
 */
class LocaleColumns
{
    /** [table => [field, ...]] for every field that has a Dari (_fa) column. */
    public static function translatable(): array
    {
        $map = [];
        $database = DB::connection()->getDatabaseName();
        foreach (Schema::getTables() as $t) {
            // Laravel 12 lists tables of every database on the server: keep only this site's.
            if (isset($t['schema']) && $t['schema'] !== $database) {
                continue;
            }
            $table = $t['name'];
            if (isset($map[$table])) {
                continue;
            }
            foreach (Schema::getColumnListing($table) as $col) {
                if (str_ends_with($col, '_fa')) {
                    $map[$table][] = substr($col, 0, -3);
                }
            }
        }
        ksort($map);
        return $map;
    }

    /** Create the "<field>_<code>" columns for a language (skips ones that exist). Returns how many were added. */
    public static function add(string $code): int
    {
        if (!preg_match('/^[a-z]{2,3}$/', $code) || in_array($code, ['en', 'fa'], true)) {
            throw new \InvalidArgumentException("Invalid language code: $code");
        }
        $added = 0;
        foreach (self::translatable() as $table => $fields) {
            foreach ($fields as $field) {
                $col = $field.'_'.$code;
                if (!Schema::hasColumn($table, $col)) {
                    // TEXT keeps tables far below MySQL's row-size limit, however many languages are added.
                    DB::statement("ALTER TABLE `$table` ADD `$col` TEXT NULL DEFAULT NULL");
                    $added++;
                }
            }
        }
        return $added;
    }

    /** Remove a language's columns (used when rolling back). */
    public static function drop(string $code): int
    {
        if (in_array($code, ['en', 'fa'], true)) {
            return 0;
        }
        $dropped = 0;
        foreach (self::translatable() as $table => $fields) {
            foreach ($fields as $field) {
                $col = $field.'_'.$code;
                if (Schema::hasColumn($table, $col)) {
                    DB::statement("ALTER TABLE `$table` DROP `$col`");
                    $dropped++;
                }
            }
        }
        return $dropped;
    }

    /** Share of translatable texts that are filled in for a language (only where English exists). */
    public static function completeness(string $code): array
    {
        $filled = 0;
        $total = 0;
        foreach (self::translatable() as $table => $fields) {
            foreach ($fields as $field) {
                $col = $field.'_'.$code;
                if ($field === 'slug' || !Schema::hasColumn($table, $col)) {
                    continue;
                }
                $total += DB::table($table)->whereNotNull($field)->where($field, '!=', '')->count();
                $filled += DB::table($table)->whereNotNull($field)->where($field, '!=', '')
                    ->whereNotNull($col)->where($col, '!=', '')->count();
            }
        }
        return ['filled' => $filled, 'total' => $total, 'percent' => $total ? (int) round(100 * $filled / $total) : 0];
    }
}
