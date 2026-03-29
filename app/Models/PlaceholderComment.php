<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlaceholderComment extends Model
{
    protected $fillable = [
        'external_id',
        'placeholder_post_id',
        'name',
        'email',
        'body',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(PlaceholderPost::class, 'placeholder_post_id');
    }
}
