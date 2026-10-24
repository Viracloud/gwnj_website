<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollResponse extends Model
{
    protected $fillable = ['poll_id', 'voter_token', 'ip_hash'];

    public function answers()
    {
        return $this->hasMany(PollAnswer::class);
    }
}
