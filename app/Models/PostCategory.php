<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
