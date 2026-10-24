<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    protected $fillable = [
        'title', 'title_fa', 'slug', 'publication_category_id',
        'description', 'description_fa', 'cover_image', 'file',
        'video_url', 'published_date', 'status',
    ];

    /** True when the publication has a video link or an uploaded video file. */
    public function hasVideo(): bool
    {
        return (bool) ($this->video_url || $this->video_file);
    }

    /** The address the video player should open. */
    public function videoSource(): ?string
    {
        if ($this->video_file) {
            return asset('uploads/'.$this->video_file);
        }
        return $this->video_url ?: null;
    }


    public function category()
    {
        return $this->belongsTo(PublicationCategory::class, 'publication_category_id');
    }
}
