<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'slug',
        'release_year',
        'release_date',
        'duration',
        'language',
        'country',
        'director',
        'cast',
        'thumbnail',
        'banner',
        'trailer_url',
        'rating',
        'view_count',
        'status',
        'content_rating',
        'is_featured',
        'is_premium',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'float',
            'view_count' => 'integer',
            'is_featured' => 'boolean',
            'is_premium' => 'boolean',
            'published_at' => 'datetime',
            'release_date' => 'date',
        ];
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'movie_genre');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(MovieView::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(MovieVideo::class);
    }

    public function getPosterUrlAttribute(): ?string
    {
        return $this->thumbnail;
    }

    public function getBackdropUrlAttribute(): ?string
    {
        return $this->banner ?? $this->thumbnail;
    }
}
