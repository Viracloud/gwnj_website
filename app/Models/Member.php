<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    public function category()
    {
        return $this->belongsTo(MemberCategory::class, 'member_category_id');
    }

    public function awards()
    {
        return $this->hasMany(MemberAward::class);
    }
}
