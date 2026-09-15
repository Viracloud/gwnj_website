<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicationCategory extends Model
{
    protected $fillable = ['name', 'name_sv', 'slug'];

    public function publications()
    {
        return $this->hasMany(Publication::class, 'publication_category_id');
    }
}
