<?php

namespace App\Services;

use App\Models\MovieVideo;
use Illuminate\Support\Str;

class VideoServiceV2
{
    protected $supabaseStorage;
    
    public function __construct(SupabaseStorageService $supabaseStorage)
    {
        $this->supabaseStorage = $supabaseStorage;
    }

    /**
     * Upload video to Supabase Storage using REST API
     */
    public function uploadVideo($file, $movieId, $videoType = 'movie', $isPrimary = false)
    {
        // Validate file
        $this->validateVideoFile($file);

        // Generate unique filename
        $filename = $this->generateVideoFilename($file, $movieId, $videoType);
        
        // Path in bucket
        $path = "videos/{$movieId}/{$filename}";
        
        \Log::info('Uploading video via REST API:', [
            'path' => $path,
            'size' => $file->getSize(),
            'mime' => $file->getMimeType(),
        ]);
        
        // Upload using Supabase REST API
        $result = $this->supabaseStorage->upload($file, $path, 'videos');
        
        if (!$result['success']) {
            throw new \Exception('Failed to upload video to Supabase');
        }
        
        // Get video duration and file size
        $duration = $this->getVideoDuration($file);
        $fileSize = $file->getSize(); // Store in bytes (integer)
        
        // Create video record
        $video = MovieVideo::create([
            'movie_id' => $movieId,
            'title' => $this->generateVideoTitle($videoType),
            'video_url' => $result['url'],
            'video_type' => $videoType,
            'quality' => $this->detectVideoQuality($file),
            'duration' => $duration,
            'file_size' => $fileSize,
            'is_primary' => $isPrimary,
        ]);
        
        \Log::info('Video record created:', [
            'id' => $video->id,
            'url' => $video->video_url,
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
     * Delete video
     */
    public function deleteVideo(MovieVideo $video)
    {
        // Delete file from Supabase if it's not an external URL
        if (str_contains($video->video_url, env('SUPABASE_URL'))) {
            try {
                // Extract path from URL
                $urlParts = parse_url($video->video_url);
                $path = $urlParts['path'] ?? '';
                // Remove /storage/v1/object/public/videos/ prefix
                $path = preg_replace('#^/storage/v1/object/public/videos/#', '', $path);
                
                if ($path) {
                    $this->supabaseStorage->delete($path, 'videos');
                }
            } catch (\Exception $e) {
                \Log::error('Failed to delete video file: ' . $e->getMessage());
            }
        }
        
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
        // Placeholder - requires FFmpeg for accurate duration
        return null;
    }

    /**
     * Detect video quality (basic implementation)
     */
    protected function detectVideoQuality($file)
    {
        // Placeholder - requires FFmpeg for accurate quality detection
        return null;
    }
}
