<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeText extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    protected $fillable = ['key', 'value', 'value_fa'];
}
