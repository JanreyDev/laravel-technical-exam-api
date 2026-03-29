<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlaceholderAlbum extends Model
{
    protected $fillable = [
        'external_id',
        'placeholder_user_id',
        'title',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(PlaceholderUser::class, 'placeholder_user_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(PlaceholderPhoto::class, 'placeholder_album_id');
    }
}
