<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlaceholderCompany extends Model
{
    protected $fillable = [
        'placeholder_user_id',
        'name',
        'catch_phrase',
        'bs',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(PlaceholderUser::class, 'placeholder_user_id');
    }
}
