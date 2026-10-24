<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageContactItem extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    protected $table = 'page_contact_items';
}
