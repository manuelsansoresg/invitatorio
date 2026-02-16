<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (Category $category): void {
            $category->invitations()->each(function (Invitation $invitation): void {
                $invitation->delete();
            });
        });
    }
}
