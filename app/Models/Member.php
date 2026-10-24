<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    public function category()
    {
        return $this->belongsTo(MemberCategory::class, 'member_category_id');
    }

    public function awards()
    {
        return $this->hasMany(MemberAward::class);
    }
}
