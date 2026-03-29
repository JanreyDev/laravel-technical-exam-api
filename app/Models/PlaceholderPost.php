<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlaceholderPost extends Model
{
    protected $fillable = [
        'external_id',
        'placeholder_user_id',
        'title',
        'body',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(PlaceholderUser::class, 'placeholder_user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PlaceholderComment::class, 'placeholder_post_id');
    }
}
