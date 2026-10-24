<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomPageImage extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    protected $fillable = ['custom_page_id', 'image', 'caption', 'caption_fa', 'sort_order'];

    public function customPage()
    {
        return $this->belongsTo(CustomPage::class);
    }
}
