<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\URL;

class Link extends Model
{
    use HasFactory;

    protected $guarded = ['is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function linkHits(): HasMany
    {
        return $this->hasMany(LinkHit::class);
    }

    public function signedLink(): Attribute
    {
        return Attribute::make(
            get: fn(null $value, array $attributes): string => URL::temporarySignedRoute('link.stats', now()->addMinutes(10), ['slug' => $attributes['slug']]),
        );
    }
}
