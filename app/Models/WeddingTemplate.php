<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeddingTemplate extends Model
{
    protected $guarded = [];

    protected $casts = [
        'gallery_images' => 'array',
        'event_date' => 'datetime',
    ];
}
