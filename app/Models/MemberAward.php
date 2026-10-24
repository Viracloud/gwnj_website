<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberAward extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
