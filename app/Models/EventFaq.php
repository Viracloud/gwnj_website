<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventFaq extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
