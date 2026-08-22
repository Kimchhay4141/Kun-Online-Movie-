<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Movie;
use App\Services\SupabaseStorageService;
use Illuminate\Http\UploadedFile;

echo "🔄 Migrating ALL Images to Supabase...\n\n";

$supabaseStorage = new SupabaseStorageService();
$movies = Movie::all();

$thumbnailsMigrated = 0;
$bannersMigrated = 0;
$failed = 0;
$skipped = 0;

foreach ($movies as $movie) {
    echo "Processing: {$movie->title} (ID: {$movie->id})\n";
    
    // Migrate Thumbnail
    if ($movie->thumbnail && !str_contains($movie->thumbnail, 'supabase.co')) {
        $localPath = storage_path('app/public/' . $movie->thumbnail);
        
        if (file_exists($localPath)) {
            try {
                $filename = basename($movie->thumbnail);
                $mimeType = mime_content_type($localPath);
                
                $uploadedFile = new UploadedFile($localPath, $filename, $mimeType, null, true);
                $supabasePath = 'posters/' . time() . '_' . uniqid() . '_' . $filename;
                $result = $supabaseStorage->upload($uploadedFile, $supabasePath, 'posters');
                
                $movie->update(['thumbnail' => $result['url']]);
                echo "  ✅ Thumbnail migrated\n";
                $thumbnailsMigrated++;
                
                sleep(1); // Prevent rate limiting
            } catch (\Exception $e) {
                echo "  ❌ Thumbnail failed: " . $e->getMessage() . "\n";
                $failed++;
            }
        } else {
            echo "  ⚠️  Thumbnail file not found\n";
        }
    } else if ($movie->thumbnail && str_contains($movie->thumbnail, 'supabase.co')) {
        echo "  ✓ Thumbnail already in Supabase\n";
        $skipped++;
    }
    
    // Migrate Banner
    if ($movie->banner && !str_contains($movie->banner, 'supabase.co')) {
        $localPath = storage_path('app/public/' . $movie->banner);
        
        if (file_exists($localPath)) {
            try {
                $filename = basename($movie->banner);
                $mimeType = mime_content_type($localPath);
                
                $uploadedFile = new UploadedFile($localPath, $filename, $mimeType, null, true);
                $supabasePath = 'posters/banners_' . time() . '_' . uniqid() . '_' . $filename;
                $result = $supabaseStorage->upload($uploadedFile, $supabasePath, 'posters');
                
                $movie->update(['banner' => $result['url']]);
                echo "  ✅ Banner migrated\n";
                $bannersMigrated++;
                
                sleep(1); // Prevent rate limiting
            } catch (\Exception $e) {
                echo "  ❌ Banner failed: " . $e->getMessage() . "\n";
                $failed++;
            }
        } else {
            echo "  ⚠️  Banner file not found\n";
        }
    } else if ($movie->banner && str_contains($movie->banner, 'supabase.co')) {
        echo "  ✓ Banner already in Supabase\n";
    }
    
    echo "\n";
}

echo str_repeat("=", 60) . "\n";
echo "📊 MIGRATION SUMMARY\n";
echo str_repeat("=", 60) . "\n";
echo "Total movies: " . $movies->count() . "\n";
echo "Thumbnails migrated: $thumbnailsMigrated\n";
echo "Banners migrated: $bannersMigrated\n";
echo "Already in Supabase: $skipped\n";
echo "Failed: $failed\n";
echo "\n✅ Migration completed!\n";
