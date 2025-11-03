<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Link extends Model
{
    use HasFactory;

    protected $attributes = [
        'is_active' => 'boolean',
    ];

    public function linkHits(): HasMany
    {
        return $this->hasMany(LinkHit::class);
    }
}
