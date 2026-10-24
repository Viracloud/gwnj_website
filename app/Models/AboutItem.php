<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutItem extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    /**
     * All photos for the about slideshow: the main image first,
     * then the extra slideshow images in their sort order.
     */
    public function slideshowImages(): array
    {
        $extra = \Illuminate\Support\Facades\Schema::hasTable('about_item_images')
            ? AboutItemImage::orderBy('sort_order')->orderBy('id')->pluck('image')->all()
            : [];

        return array_values(array_filter(array_merge([$this->image], $extra)));
    }
}
