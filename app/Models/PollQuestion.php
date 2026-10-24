<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollQuestion extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    protected $fillable = ['poll_id', 'question', 'question_fa', 'type', 'required', 'sort_order'];

    protected $casts = ['required' => 'boolean'];

    public const TYPES = [
        'single' => 'Single choice',
        'multiple' => 'Multiple choice',
        'rating' => 'Rating 1–5',
        'text' => 'Free text (admin only)',
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function options()
    {
        return $this->hasMany(PollOption::class)->orderBy('sort_order')->orderBy('id');
    }

    public function answers()
    {
        return $this->hasMany(PollAnswer::class);
    }

    public function hasOptions(): bool
    {
        return in_array($this->type, ['single', 'multiple'], true);
    }
}
