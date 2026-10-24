<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    protected $fillable = [
        'campaign_category_id', 'image', 'title', 'title_fa', 'slug', 'slug_fa',
        'excerpt', 'excerpt_fa', 'content', 'content_fa',
        'status', 'goal', 'goal_fa', 'cta_text', 'cta_text_fa', 'cta_link',
        'stat_number', 'stat_label', 'stat_label_fa',
        'countries', 'countries_fa', 'published_date', 'sort_order', 'show_on_home',
        'seo_title', 'seo_title_fa', 'seo_meta_description', 'seo_meta_description_fa',
    ];

    protected $casts = [
        'published_date' => 'date',
        'show_on_home' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(CampaignCategory::class, 'campaign_category_id');
    }
}
