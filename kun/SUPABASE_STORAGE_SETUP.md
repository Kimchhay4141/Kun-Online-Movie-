# Supabase Storage Setup for Video Management

This guide explains how to set up and use Supabase Storage for video uploads in your Kun Online Movie project.

## Prerequisites

- ✅ Supabase account with a project
- ✅ Supabase Storage enabled
- ✅ Laravel project configured with Supabase database

## Step 1: Configure Supabase Storage Credentials

### Get Supabase Storage Credentials

1. Go to your Supabase Dashboard
2. Navigate to **Settings** → **API**
3. Copy the following information:
   - **Project URL**: Your Supabase project URL
   - **Storage URL**: Usually `https://[project-ref].supabase.co/storage/v1`
   - **Storage Key**: Your service_role key (for backend operations)

### Create Storage Bucket

1. Go to **Storage** section in Supabase Dashboard
2. Create a new bucket called `videos`
3. Make it **public** if you want videos to be accessible without authentication
4. Configure bucket policies as needed

### Update .env File

Add these variables to your `.env` file:

```env
# Supabase Storage Configuration
SUPABASE_STORAGE_KEY=your-service-role-key
SUPABASE_STORAGE_SECRET=your-service-role-secret
SUPABASE_STORAGE_REGION=us-east-1
SUPABASE_STORAGE_BUCKET=videos
SUPABASE_STORAGE_URL=https://your-project-ref.supabase.co/storage/v1
SUPABASE_STORAGE_ENDPOINT=https://your-project-ref.supabase.co/storage/v1/s3
```

**Note**: 
- For Supabase Storage, both key and secret are typically the same (your service_role key)
- The endpoint format uses `/s3` because Supabase Storage is S3-compatible
- Region is typically `us-east-1` but may vary based on your project location

## Step 2: Storage Configuration

The filesystem configuration has been updated in `config/filesystems.php` with a `supabase` disk:

```php
'supabase' => [
    'driver' => 's3',
    'key' => env('SUPABASE_STORAGE_KEY'),
    'secret' => env('SUPABASE_STORAGE_SECRET'),
    'region' => env('SUPABASE_STORAGE_REGION', 'us-east-1'),
    'bucket' => env('SUPABASE_STORAGE_BUCKET'),
    'url' => env('SUPABASE_STORAGE_URL'),
    'endpoint' => env('SUPABASE_STORAGE_ENDPOINT'),
    'use_path_style_endpoint' => true,
    'throw' => false,
    'report' => false,
],
```

## Step 3: Using the VideoService

The `VideoService` class provides methods for managing video uploads and operations.

### Basic Usage Examples

#### Upload a Video

```php
use App\Services\VideoService;

$videoService = app(VideoService::class);

// Upload from file
$video = $videoService->uploadVideo(
    $request->file('video'), 
    $movieId, 
    'movie', 
    true // is primary
);
```

#### Upload Video from URL

```php
// For external video sources (YouTube, Vimeo, etc.)
$video = $videoService->uploadVideoFromUrl(
    'https://example.com/video.mp4',
    $movieId,
    'trailer',
    false
);
```

#### Update Video

```php
$video = $videoService->updateVideo($video, [
    'title' => 'New Title',
    'quality' => '1080p',
    'file' => $request->file('new_video') // optional
]);
```

#### Delete Video

```php
$videoService->deleteVideo($video);
```

#### Set Primary Video

```php
$videoService->setPrimaryVideo($video);
```

#### Get Movie Videos

```php
// Get all videos for a movie
$videos = $videoService->getMovieVideos($movieId);

// Get primary video only
$primaryVideo = $videoService->getPrimaryVideo($movieId);
```

## Step 4: Controller Integration

### Example VideoController

```php
<?php

namespace App\Http\Controllers;

use App\Services\VideoService;
use App\Models\MovieVideo;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    protected $videoService;

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }

    public function store(Request $request, $movieId)
    {
        $request->validate([
            'video' => 'required|file|mimes:mp4,webm,ogg,mov,avi,mkv|max:2048000', // 2GB max
            'video_type' => 'required|in:movie,trailer,teaser,behind_scenes,clip',
            'is_primary' => 'boolean'
        ]);

        $video = $this->videoService->uploadVideo(
            $request->file('video'),
            $movieId,
            $request->video_type,
            $request->boolean('is_primary', false)
        );

        return response()->json($video, 201);
    }

    public function update(Request $request, MovieVideo $video)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'quality' => 'sometimes|in:480p,720p,1080p,4K',
            'file' => 'sometimes|file|mimes:mp4,webm,ogg,mov,avi,mkv|max:2048000'
        ]);

        $video = $this->videoService->updateVideo($video, $request->all());

        return response()->json($video);
    }

    public function destroy(MovieVideo $video)
    {
        $this->videoService->deleteVideo($video);

        return response()->json(['message' => 'Video deleted successfully']);
    }

    public function setPrimary(MovieVideo $video)
    {
        $video = $this->videoService->setPrimaryVideo($video);

        return response()->json($video);
    }
}
```

## Step 5: Video Storage Structure

Videos will be stored in Supabase Storage with the following structure:

```
videos/
├── {movie_id}/
│   ├── movie_{movie_id}_{timestamp}_{random}.mp4
│   ├── trailer_{movie_id}_{timestamp}_{random}.mp4
│   └── teaser_{movie_id}_{timestamp}_{random}.mp4
```

## Step 6: MovieVideo Model Features

The `MovieVideo` model includes helpful methods:

```php
// Get full video URL
$video->full_url;

// Get storage path
$video->storage_path;

// Check if external URL
$video->isExternalUrl();

// Human-readable duration
$video->human_duration; // "1:23:45" or "5:30"

// Human-readable file size
$video->human_file_size; // "1.5 GB" or "250 MB"
```

### Query Scopes

```php
// Get primary videos only
MovieVideo::primary()->get();

// Get videos by type
MovieVideo::byType('trailer')->get();

// Get videos by quality
MovieVideo::byQuality('1080p')->get();
```

## Step 7: Advanced Features

### FFmpeg Integration (Optional)

For accurate video duration and quality detection, you can integrate FFmpeg:

1. Install FFmpeg on your server
2. Install the PHP-FFMpeg package:
```bash
composer require php-ffmpeg/php-ffmpeg
```

3. Update the `VideoService` methods `getVideoDuration()` and `detectVideoQuality()` with FFmpeg implementation.

### Video Streaming

For better video streaming performance, consider:

1. **HLS/DASH**: Convert videos to HLS or DASH format for adaptive streaming
2. **CDN**: Use Supabase's built-in CDN or configure a custom CDN
3. **Thumbnail Generation**: Generate video thumbnails for preview

### Storage Policies

Configure Supabase Storage policies for security:

```sql
-- Allow public read access
CREATE POLICY "Public videos are viewable by everyone"
ON storage.objects FOR SELECT
TO anon, authenticated
USING (bucket_id = 'videos');

-- Allow authenticated users to upload
CREATE POLICY "Authenticated users can upload videos"
ON storage.objects FOR INSERT
TO authenticated
WITH CHECK (bucket_id = 'videos');
```

## Step 8: Testing

### Test Video Upload

```php
// In your controller or tests
public function testVideoUpload()
{
    $videoService = app(VideoService::class);
    
    // Create a test video file
    $file = UploadedFile::fake()->create('test_video.mp4', 10000); // 10MB
    
    $video = $videoService->uploadVideo($file, 1, 'movie', true);
    
    $this->assertDatabaseHas('movie_videos', [
        'movie_id' => 1,
        'video_type' => 'movie',
        'is_primary' => true
    ]);
    
    $this->assertNotNull($video->video_url);
}
```

## Troubleshooting

### Common Issues

1. **Connection Error**: Check your Supabase credentials and endpoint URL
2. **Permission Denied**: Ensure your service_role key has storage permissions
3. **File Size Error**: Check PHP upload limits and Supabase storage limits
4. **Invalid File Type**: Ensure video format is supported

### Debugging

Enable Laravel logging to see detailed error messages:

```env
LOG_LEVEL=debug
```

Check logs in `storage/logs/laravel.log` for detailed error information.

## Best Practices

1. **File Size**: Keep videos under 2GB for optimal performance
2. **Format**: Use MP4 with H.264 codec for best compatibility
3. **Quality**: Provide multiple quality options (480p, 720p, 1080p)
4. **Backup**: Enable Supabase backups for your storage bucket
5. **Monitoring**: Monitor storage usage and costs in Supabase dashboard

## Security Considerations

1. **Environment Variables**: Never commit `.env` file with real credentials
2. **Access Control**: Use appropriate RLS policies for storage access
3. **File Validation**: Always validate uploaded files on the server side
4. **Rate Limiting**: Implement rate limiting for upload endpoints

## Support

- Supabase Documentation: https://supabase.com/docs/guides/storage
- Laravel Filesystem: https://laravel.com/docs/filesystem
- Project Issues: Check existing issues or create new ones

---

**Last Updated**: Ready to use Supabase Storage for videos! 🚀