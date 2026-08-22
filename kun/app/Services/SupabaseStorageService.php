<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseStorageService
{
    protected $url;
    protected $key;
    protected $bucket;

    public function __construct()
    {
        $this->url = env('SUPABASE_URL');
        $this->key = env('SUPABASE_KEY', env('SUPABASE_SERVICE_ROLE_KEY'));
        $this->bucket = 'videos'; // default bucket
    }

    /**
     * Upload file to Supabase Storage using REST API
     */
    public function upload($file, $path, $bucket = null)
    {
        $bucket = $bucket ?? $this->bucket;
        
        // Build full storage URL
        $storageUrl = "{$this->url}/storage/v1/object/{$bucket}/{$path}";
        
        Log::info('Uploading to Supabase:', [
            'url' => $storageUrl,
            'path' => $path,
            'bucket' => $bucket,
            'file_size' => $file->getSize(),
        ]);
        
        // Read file content
        $fileContent = file_get_contents($file->getPathname());
        
        // Upload using HTTP client
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->key}",
            'Content-Type' => $file->getMimeType(),
        ])
        ->withOptions(['verify' => false]) // Disable SSL verification for Windows
        ->withBody($fileContent, $file->getMimeType())
        ->post($storageUrl);
        
        if ($response->successful()) {
            // Return the public URL
            $publicUrl = "{$this->url}/storage/v1/object/public/{$bucket}/{$path}";
            
            Log::info('Upload successful:', [
                'public_url' => $publicUrl,
                'response' => $response->json(),
            ]);
            
            return [
                'path' => $path,
                'url' => $publicUrl,
                'success' => true,
            ];
        } else {
            Log::error('Upload failed:', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            
            throw new \Exception('Failed to upload to Supabase: ' . $response->body());
        }
    }

    /**
     * Delete file from Supabase Storage
     */
    public function delete($path, $bucket = null)
    {
        $bucket = $bucket ?? $this->bucket;
        
        $storageUrl = "{$this->url}/storage/v1/object/{$bucket}/{$path}";
        
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->key}",
        ])
        ->withOptions(['verify' => false])
        ->delete($storageUrl);
        
        return $response->successful();
    }

    /**
     * Check if file exists
     */
    public function exists($path, $bucket = null)
    {
        $bucket = $bucket ?? $this->bucket;
        
        $storageUrl = "{$this->url}/storage/v1/object/{$bucket}/{$path}";
        
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->key}",
        ])
        ->withOptions(['verify' => false])
        ->head($storageUrl);
        
        return $response->successful();
    }

    /**
     * List files in a directory
     */
    public function list($prefix = '', $bucket = null)
    {
        $bucket = $bucket ?? $this->bucket;
        
        $storageUrl = "{$this->url}/storage/v1/object/list/{$bucket}";
        
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->key}",
        ])
        ->withOptions(['verify' => false])
        ->post($storageUrl, [
            'prefix' => $prefix,
            'limit' => 100,
            'offset' => 0,
        ]);
        
        if ($response->successful()) {
            return $response->json();
        }
        
        return [];
    }
}
