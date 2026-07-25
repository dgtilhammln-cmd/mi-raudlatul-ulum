<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramFeed extends Model
{
    protected $fillable = ['image_path', 'link_url', 'caption', 'order', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];

    public function scopeActive($q) { return $q->where('is_active', true)->orderBy('order'); }
}
