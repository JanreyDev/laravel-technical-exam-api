<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlaceholderAddress extends Model
{
    protected $fillable = [
        'placeholder_user_id',
        'street',
        'suite',
        'city',
        'zipcode',
        'lat',
        'lng',
    ];

    protected function casts(): array
    {
        return [
            'lat' => 'decimal:7',
            'lng' => 'decimal:7',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(PlaceholderUser::class, 'placeholder_user_id');
    }
}
