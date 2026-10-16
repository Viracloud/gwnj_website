<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'image', 'title', 'title_sv', 'slug', 'slug_sv',
        'excerpt', 'excerpt_sv', 'content', 'content_sv',
        'status', 'goal', 'goal_sv', 'cta_text', 'cta_text_sv', 'cta_link',
        'stat_number', 'stat_label', 'stat_label_sv',
        'countries', 'countries_sv', 'published_date', 'sort_order', 'show_on_home',
        'seo_title', 'seo_title_sv', 'seo_meta_description', 'seo_meta_description_sv',
    ];

    protected $casts = [
        'published_date' => 'date',
        'show_on_home' => 'boolean',
    ];
}
