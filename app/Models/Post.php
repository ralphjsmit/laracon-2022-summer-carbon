<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function status(): Attribute
    {
        return Attribute::get(function ($value, array $attributes) {
            if (blank($attributes['published_at'])) {
                return 'draft';
            }

            return Carbon::parse($attributes['published_at'])->isFuture()
                ? 'scheduled'
                : 'published';
        });
    }
}
