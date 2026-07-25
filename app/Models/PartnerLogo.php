<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerLogo extends Model
{
    protected $fillable = ['name', 'image_path', 'url', 'type', 'order', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];

    public function scopePartners($q) { return $q->where('type', 'partner')->where('is_active', true)->orderBy('order'); }
    public function scopeSponsors($q) { return $q->where('type', 'sponsor')->where('is_active', true)->orderBy('order'); }
    public function scopeActive($q)   { return $q->where('is_active', true)->orderBy('order'); }
}
