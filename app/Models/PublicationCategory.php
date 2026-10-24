<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicationCategory extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    protected $fillable = ['name', 'name_fa', 'slug'];

    public function publications()
    {
        return $this->hasMany(Publication::class, 'publication_category_id');
    }
}
