<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $fillable = [
        'title', 'title_sv', 'slug', 'publication_category_id',
        'description', 'description_sv', 'cover_image', 'file',
        'published_date', 'status',
    ];

    public function category()
    {
        return $this->belongsTo(PublicationCategory::class, 'publication_category_id');
    }
}
