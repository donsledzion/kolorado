<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColoringGrid extends Model
{
    protected $fillable = [
        'original_filename',
        'original_image_path',
        'processed_image_path',
        'grid_width',
        'grid_height',
        'color_count',
        'grid_data',
        'color_palette',
    ];

    protected $casts = [
        'grid_data' => 'array',
        'color_palette' => 'array',
    ];
}
