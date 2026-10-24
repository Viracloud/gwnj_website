<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    protected $fillable = ['name', 'name_fa', 'logo', 'website', 'sort_order', 'status'];
}
