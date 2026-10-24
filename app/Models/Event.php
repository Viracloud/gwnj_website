<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    public function event_faqs()
    {
        return $this->hasMany(EventFaq::class);
    }

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id');
    }
}
