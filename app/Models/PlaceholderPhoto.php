<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlaceholderPhoto extends Model
{
    protected $fillable = [
        'external_id',
        'placeholder_album_id',
        'title',
        'url',
        'thumbnail_url',
    ];

    public function album(): BelongsTo
    {
        return $this->belongsTo(PlaceholderAlbum::class, 'placeholder_album_id');
    }
}
