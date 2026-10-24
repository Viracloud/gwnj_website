<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagePrivacyItem extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    protected $table = 'page_privacy_items';
}
