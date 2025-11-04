<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LinkHit extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $guarded = [];

    public function ip(): Attribute
    {
        return Attribute::make(
            get: fn(string $value): string => preg_replace("/(?<=\.)\d{1,3}(?=$)/", '0', $value),
        );
    }

    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }
}
