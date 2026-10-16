<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomPage extends Model
{
    public function images()
    {
        return $this->hasMany(CustomPageImage::class)->orderBy('sort_order')->orderBy('id');
    }
}
