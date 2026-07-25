<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingImage extends Model
{
    protected $fillable = [
        'image_path',
        'column_position',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
