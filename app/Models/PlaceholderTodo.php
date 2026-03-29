<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlaceholderTodo extends Model
{
    protected $fillable = [
        'external_id',
        'placeholder_user_id',
        'title',
        'completed',
    ];

    protected function casts(): array
    {
        return [
            'completed' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(PlaceholderUser::class, 'placeholder_user_id');
    }
}
