<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomPageImage extends Model
{
    protected $fillable = ['custom_page_id', 'image', 'caption', 'caption_sv', 'sort_order'];

    public function customPage()
    {
        return $this->belongsTo(CustomPage::class);
    }
}
