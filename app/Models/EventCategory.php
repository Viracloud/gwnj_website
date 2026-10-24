<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCategory extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    protected $fillable = ['name', 'name_fa', 'slug'];

    public function events()
    {
        return $this->hasMany(Event::class, 'event_category_id');
    }
}
