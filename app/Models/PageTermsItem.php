<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageTermsItem extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    protected $table = 'page_terms_items';
}
