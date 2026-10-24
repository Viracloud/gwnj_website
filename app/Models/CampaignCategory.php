<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignCategory extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    protected $fillable = ['name', 'name_fa', 'slug'];

    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'campaign_category_id');
    }
}
