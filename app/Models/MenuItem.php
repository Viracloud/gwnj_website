<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'parent_id', 'label', 'label_sv', 'link_type', 'route_name',
        'custom_page_id', 'url', 'open_new_tab', 'is_button', 'order', 'status',
        'show_in_footer', 'footer_column',
    ];

    public static function availableRoutes(): array
    {
        return [
            'home' => 'Home',
            'about' => 'About',
            'events' => 'Events',
            'blog' => 'Blog / News',
            'members' => 'Team members',
            'gallery' => 'Gallery',
            'faq' => 'FAQ',
            'contact' => 'Contact',
            'donate' => 'Donate',
            'login' => 'Login',
            'register' => 'Register',
            'terms' => 'Terms of use',
            'privacy' => 'Privacy policy',
        ];
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    public function visibleChildren()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->where('status', 'Show')->orderBy('order');
    }

    public function customPage()
    {
        return $this->belongsTo(CustomPage::class, 'custom_page_id');
    }

    public function label(): string
    {
        return tf($this, 'label') ?? $this->label;
    }

    public function resolvedUrl(): string
    {
        if ($this->link_type === 'custom_page' && $this->customPage) {
            return route('custom_page', $this->customPage->slug);
        }

        if ($this->link_type === 'route' && $this->route_name && \Illuminate\Support\Facades\Route::has($this->route_name)) {
            return route($this->route_name);
        }

        if ($this->link_type === 'url' && $this->url) {
            return $this->url;
        }

        return '#';
    }

    public function isActive(): bool
    {
        if ($this->link_type === 'route' && $this->route_name && \Illuminate\Support\Facades\Route::has($this->route_name)) {
            return request()->routeIs($this->route_name) || request()->fullUrlIs($this->resolvedUrl());
        }

        if ($this->link_type === 'custom_page' && $this->customPage) {
            return request()->is('page/' . $this->customPage->slug);
        }

        return false;
    }
}
