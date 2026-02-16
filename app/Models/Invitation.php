<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Invitation extends Model
{
    protected $fillable = [
        'category_id',
        'slug',
        'cover_photo_path',
        'background_music_path',
        'background_music_autoplay',
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
        'background_music_autoplay' => 'boolean',
        'design_settings' => 'array',
        'content_blocks' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (Invitation $invitation): void {
            $paths = [];

            if ($invitation->cover_photo_path) {
                $paths[] = $invitation->cover_photo_path;
            }

            if ($invitation->background_music_path) {
                $paths[] = $invitation->background_music_path;
            }

            $design = $invitation->design_settings ?? [];
            $designImageKeys = [
                'dedication',
                'details',
                'ceremony',
                'reception',
                'extra_info',
                'rsvp',
            ];

            foreach ($designImageKeys as $key) {
                if (
                    isset($design[$key]['background_image'])
                    && is_string($design[$key]['background_image'])
                    && $design[$key]['background_image'] !== ''
                ) {
                    $paths[] = $design[$key]['background_image'];
                }
            }

            $blocks = $invitation->content_blocks ?? [];

            foreach ($blocks as $block) {
                $data = $block['data'] ?? [];

                foreach ($data as $field => $value) {
                    if (! is_string($value) || $value === '') {
                        continue;
                    }

                    if (
                        str_contains($field, 'image')
                        || $field === 'video'
                        || $field === 'background_image'
                    ) {
                        $paths[] = $value;
                    }
                }
            }

            $disk = Storage::disk('public_uploads');

            foreach (array_unique($paths) as $path) {
                $disk->delete($path);
            }
        });
    }
}
