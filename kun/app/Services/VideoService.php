<?php

namespace App\Services;

use App\Models\MovieVideo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoService
{
    /**
     * Upload video to Supabase Storage
     */
    public function uploadVideo($file, $movieId, $videoType = 'movie', $isPrimary = false)
    {
        // Validate file
        $this->validateVideoFile($file);

        // Generate unique filename
        $filename = $this->generateVideoFilename($file, $movieId, $videoType);
        
        // Upload to Supabase Storage
        $path = $file->storeAs('videos/' . $movieId, $filename, 'supabase');
        
        // Get video duration and file size
        $duration = $this->getVideoDuration($file);
        $fileSize = $file->getSize() / (1024 * 1024); // Convert to MB
        
        // Create video record
        $video = MovieVideo::create([
            'movie_id' => $movieId,
            'title' => $this->generateVideoTitle($videoType),
            'video_url' => Storage::disk('supabase')->url($path),
            'video_type' => $videoType,
            'quality' => $this->detectVideoQuality($file),
            'duration' => $duration,
            'file_size' => round($fileSize, 2),
            'is_primary' => $isPrimary,
        ]);
        
        return $video;
    }

    /**
     * Upload video from URL (for external video sources)
     */
    public function uploadVideoFromUrl($url, $movieId, $videoType = 'movie', $isPrimary = false)
    {
        // Validate URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Invalid video URL provided');
        }

        // Create video record with external URL
        $video = MovieVideo::create([
            'movie_id' => $movieId,
            'title' => $this->generateVideoTitle($videoType),
            'video_url' => $url,
            'video_type' => $videoType,
            'quality' => null,
            'duration' => null,
            'file_size' => null,
            'is_primary' => $isPrimary,
        ]);
        
        return $video;
    }

    /**
     * Update existing video
     */
    public function updateVideo(MovieVideo $video, array $data)
    {
        // Handle file replacement
        if (isset($data['file'])) {
            // Delete old file from Supabase
            $this->deleteVideoFile($video->video_url);
            
            // Upload new file
            $file = $data['file'];
            $this->validateVideoFile($file);
            
            $filename = $this->generateVideoFilename($file, $video->movie_id, $video->video_type);
            $path = $file->storeAs('videos/' . $video->movie_id, $filename, 'supabase');
            
            $data['video_url'] = Storage::disk('supabase')->url($path);
            $data['duration'] = $this->getVideoDuration($file);
            $data['file_size'] = round($file->getSize() / (1024 * 1024), 2);
            $data['quality'] = $this->detectVideoQuality($file);
            
            unset($data['file']);
        }

        // Update video
        $video->update($data);
        
        return $video->fresh();
    }

    /**
     * Delete video
     */
    public function deleteVideo(MovieVideo $video)
    {
        // Delete file from Supabase
        $this->deleteVideoFile($video->video_url);
        
        // Delete database record
        $video->delete();
        
        return true;
    }

    /**
     * Set video as primary
     */
    public function setPrimaryVideo(MovieVideo $video)
    {
        // Remove primary status from other videos of the same movie
        MovieVideo::where('movie_id', $video->movie_id)
            ->where('id', '!=', $video->id)
            ->update(['is_primary' => false]);
        
        // Set this video as primary
        $video->update(['is_primary' => true]);
        
        return $video->fresh();
    }

    /**
     * Get all videos for a movie
     */
    public function getMovieVideos($movieId)
    {
        return MovieVideo::where('movie_id', $movieId)
            ->orderBy('is_primary', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get primary video for a movie
     */
    public function getPrimaryVideo($movieId)
    {
        return MovieVideo::where('movie_id', $movieId)
            ->where('is_primary', true)
            ->first();
    }

    /**
     * Validate video file
     */
    protected function validateVideoFile($file)
    {
        $allowedMimes = [
            'video/mp4',
            'video/webm',
            'video/ogg',
            'video/quicktime',
            'video/x-msvideo',
            'video/x-matroska'
        ];
        
        $allowedExtensions = ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv'];
        
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \InvalidArgumentException('Invalid video file type. Allowed types: ' . implode(', ', $allowedExtensions));
        }
        
        if (!in_array(strtolower($file->getClientOriginalExtension()), $allowedExtensions)) {
            throw new \InvalidArgumentException('Invalid video file extension. Allowed extensions: ' . implode(', ', $allowedExtensions));
        }
        
        // Check file size (max 2GB)
        $maxSize = 2 * 1024 * 1024 * 1024; // 2GB in bytes
        if ($file->getSize() > $maxSize) {
            throw new \InvalidArgumentException('Video file size exceeds maximum limit of 2GB');
        }
    }

    /**
     * Generate unique video filename
     */
    protected function generateVideoFilename($file, $movieId, $videoType)
    {
        $timestamp = time();
        $random = Str::random(8);
        $extension = $file->getClientOriginalExtension();
        
        return "{$videoType}_{$movieId}_{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Generate video title based on type
     */
    protected function generateVideoTitle($videoType)
    {
        $titles = [
            'movie' => 'Main Movie',
            'trailer' => 'Official Trailer',
            'teaser' => 'Teaser',
            'behind_scenes' => 'Behind the Scenes',
            'clip' => 'Video Clip'
        ];
        
        return $titles[$videoType] ?? 'Video';
    }

    /**
     * Get video duration (basic implementation)
     */
    protected function getVideoDuration($file)
    {
        // This is a basic implementation. For accurate duration detection,
        // you might want to use FFmpeg or similar library
        try {
            // If you have FFmpeg installed, you can use:
            // $ffprobe = \FFMpeg\FFProbe::create();
            // $duration = $ffprobe->format($file->getPathname())->get('duration');
            // return (int) $duration;
            
            // For now, return null as a placeholder
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Detect video quality (basic implementation)
     */
    protected function detectVideoQuality($file)
    {
        // This is a basic implementation. For accurate quality detection,
        // you might want to use FFmpeg or similar library
        try {
            // If you have FFmpeg installed, you can use:
            // $ffprobe = \FFMpeg\FFProbe::create();
            // $video = $ffprobe->streams($file->getPathname())->videos()->first();
            // $height = $video->get('height');
            // return $this->heightToQuality($height);
            
            // For now, return null as a placeholder
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Convert video height to quality label
     */
    protected function heightToQuality($height)
    {
        if ($height >= 2160) return '4K';
        if ($height >= 1080) return '1080p';
        if ($height >= 720) return '720p';
        if ($height >= 480) return '480p';
        return '360p';
    }

    /**
     * Delete video file from Supabase Storage
     */
    protected function deleteVideoFile($videoUrl)
    {
        try {
            // Extract path from URL
            $path = parse_url($videoUrl, PHP_URL_PATH);
            if ($path) {
                // Remove leading slash
                $path = ltrim($path, '/');
                Storage::disk('supabase')->delete($path);
            }
        } catch (\Exception $e) {
            // Log error but don't throw exception
            \Log::error('Failed to delete video file: ' . $e->getMessage());
        }
    }

    /**
     * Get video statistics
     */
    public function getVideoStatistics()
    {
        return [
            'total_videos' => MovieVideo::count(),
            'total_size' => MovieVideo::sum('file_size'),
            'by_type' => MovieVideo::selectRaw('video_type, COUNT(*) as count')
                ->groupBy('video_type')
                ->get()
                ->pluck('count', 'video_type'),
            'by_quality' => MovieVideo::selectRaw('quality, COUNT(*) as count')
                ->whereNotNull('quality')
                ->groupBy('quality')
                ->get()
                ->pluck('count', 'quality'),
        ];
    }
}