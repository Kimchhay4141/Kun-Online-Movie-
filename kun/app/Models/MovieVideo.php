<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovieVideo extends Model
{
    protected $fillable = [
        'movie_id',
        'title',
        'video_url',
        'video_type',
        'quality',
        'duration',
        'file_size',
        'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'duration' => 'integer',
            'file_size' => 'integer',
            'is_primary' => 'boolean',
        ];
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}
