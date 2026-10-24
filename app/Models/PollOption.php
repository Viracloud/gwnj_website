<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    protected $fillable = ['poll_question_id', 'label', 'label_fa', 'sort_order'];

    public function question()
    {
        return $this->belongsTo(PollQuestion::class, 'poll_question_id');
    }
}
