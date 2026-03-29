<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PlaceholderUser extends Model
{
    protected $fillable = [
        'external_id',
        'name',
        'username',
        'email',
        'phone',
        'website',
    ];

    public function address(): HasOne
    {
        return $this->hasOne(PlaceholderAddress::class);
    }

    public function company(): HasOne
    {
        return $this->hasOne(PlaceholderCompany::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(PlaceholderPost::class);
    }

    public function albums(): HasMany
    {
        return $this->hasMany(PlaceholderAlbum::class);
    }

    public function todos(): HasMany
    {
        return $this->hasMany(PlaceholderTodo::class);
    }
}
