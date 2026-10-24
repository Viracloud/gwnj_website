<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageDonateItem extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    protected $table = 'page_donate_items';
}
