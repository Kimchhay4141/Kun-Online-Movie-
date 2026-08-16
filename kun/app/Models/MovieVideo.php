<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Get the full URL for the video
     */
    public function getFullUrlAttribute(): string
    {
        return $this->video_url;
    }

    /**
     * Get the storage path for the video
     */
    public function getStoragePathAttribute(): ?string
    {
        if ($this->isExternalUrl()) {
            return null;
        }

        $path = parse_url($this->video_url, PHP_URL_PATH);
        return $path ? ltrim($path, '/') : null;
    }

    /**
     * Check if the video is stored externally (not in Supabase Storage)
     */
    public function isExternalUrl(): bool
    {
        return !str_contains($this->video_url, Storage::disk('supabase')->url(''));
    }

    /**
     * Get the duration in human-readable format
     */
    public function getHumanDurationAttribute(): string
    {
        if (!$this->duration) {
            return 'Unknown';
        }

        $hours = floor($this->duration / 3600);
        $minutes = floor(($this->duration % 3600) / 60);
        $seconds = $this->duration % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Get the file size in human-readable format
     */
    public function getHumanFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        if ($this->file_size >= 1024) {
            return number_format($this->file_size / 1024, 2) . ' GB';
        }

        return number_format($this->file_size, 2) . ' MB';
    }

    /**
     * Scope to get only primary videos
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope to get videos by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('video_type', $type);
    }

    /**
     * Scope to get videos by quality
     */
    public function scopeByQuality($query, $quality)
    {
        return $query->where('quality', $quality);
    }
}
