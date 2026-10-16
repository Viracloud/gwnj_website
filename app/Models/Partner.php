<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $fillable = ['name', 'name_sv', 'logo', 'website', 'sort_order', 'status'];
}
