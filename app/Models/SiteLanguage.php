<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** A website language, managed in Settings → Languages (see AppServiceProvider::applySiteLanguages). */
class SiteLanguage extends Model
{
    protected $fillable = ['code', 'name', 'native', 'dir', 'status', 'is_default', 'sort_order'];

    protected $casts = ['is_default' => 'boolean'];
}
