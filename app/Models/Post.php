<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    protected $casts = ['published_at' => 'datetime'];

    public function post_category()
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}
