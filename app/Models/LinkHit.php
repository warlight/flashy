<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkHit extends Model
{
    use HasFactory;

    public function Link()
    {
        return $this->belongsTo(Link::class);
    }
}
