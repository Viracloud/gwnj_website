<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberCategory extends Model
{
    protected $fillable = ['name', 'name_sv', 'slug', 'sort_order'];

    public function members()
    {
        return $this->hasMany(Member::class, 'member_category_id')->orderBy('sort_order')->orderBy('id');
    }
}
