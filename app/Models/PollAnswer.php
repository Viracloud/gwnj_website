<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollAnswer extends Model
{
    public $timestamps = false;

    protected $fillable = ['poll_response_id', 'poll_question_id', 'poll_option_id', 'rating', 'text'];
}
