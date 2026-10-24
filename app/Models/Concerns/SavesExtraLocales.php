<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Saves translations for the extra languages (French, German ...) posted from the
 * admin panel's "other languages" fields:  xl[<table>][<locale>][<field>] = text
 * so no controller has to know about these languages.
 */
trait SavesExtraLocales
{
    public static function bootSavesExtraLocales(): void
    {
        static::saving(function ($model) {
            $request = request();
            if (!$request || !$request->is('admin/*')) {
                return;
            }
            $posted = $request->input('xl.'.$model->getTable());
            if (!is_array($posted)) {
                return;
            }
            foreach ($posted as $locale => $fields) {
                if (!in_array($locale, extra_locales(), true) || !is_array($fields)) {
                    continue;
                }
                foreach ($fields as $field => $value) {
                    $column = $field.'_'.$locale;
                    if (is_string($field) && preg_match('/^[a-z0-9_]+$/', $field) && self::hasLocaleColumn($model->getTable(), $column)) {
                        $model->{$column} = is_string($value) ? $value : null;
                    }
                }
                // A translated title without a slug gets one, unique across all languages.
                $slugCol = 'slug_'.$locale;
                if (self::hasLocaleColumn($model->getTable(), $slugCol)) {
                    $title = $model->{'title_'.$locale} ?? $model->{'name_'.$locale} ?? null;
                    $slug = Str::slug((string) ($model->{$slugCol} ?: $title));
                    if ($slug !== '') {
                        $base = $slug;
                        $i = 2;
                        while (where_slug($model->newQuery(), $slug)->when($model->exists, fn ($q) => $q->where($model->getKeyName(), '!=', $model->getKey()))->exists()) {
                            $slug = $base.'-'.$i++;
                        }
                        $model->{$slugCol} = $slug;
                    }
                }
            }
        });
    }

    private static function hasLocaleColumn(string $table, string $column): bool
    {
        static $cache = [];
        return $cache[$table][$column] ??= Schema::hasColumn($table, $column);
    }
}
