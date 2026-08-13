<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovieView extends Model
{
    protected $fillable = [
        'user_id',
        'movie_id',
        'watch_duration',
        'total_duration',
        'progress_percentage',
        'last_watched_at',
        'completed',
    ];

    protected function casts(): array
    {
        return [
            'watch_duration' => 'integer',
            'total_duration' => 'integer',
            'progress_percentage' => 'integer',
            'last_watched_at' => 'datetime',
            'completed' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}
