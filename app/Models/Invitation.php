<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    protected $fillable = [
        'category_id',
        'slug',
        'cover_photo_path',
        'background_music_path',
        'event_date',
        'dedication',
        'ceremony_location_name',
        'ceremony_address',
        'ceremony_time',
        'ceremony_map_link',
        'reception_location_name',
        'reception_address',
        'reception_time',
        'reception_map_link',
        'dress_code',
        'gift_table_link',
        'instagram_hashtags',
        'is_active',
        'rsvp_method',
        'whatsapp_number',
        'design_settings',
        'content_blocks',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'ceremony_time' => 'datetime',
        'reception_time' => 'datetime',
        'is_active' => 'boolean',
        'design_settings' => 'array',
        'content_blocks' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
