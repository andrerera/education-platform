<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class SupabaseUploader
{
    protected static $supabaseUrl;
    protected static $supabaseKey;
    protected static $bucketName;

    protected static function init()
    {
        self::$supabaseUrl = config('services.supabase.url');
        self::$supabaseKey = config('services.supabase.key');
        self::$bucketName = config('services.supabase.bucket', 'edufiles');
        if (!self::$supabaseUrl || !self::$supabaseKey || !self::$bucketName) {
            Log::error('Supabase configuration missing', [
                'url' => self::$supabaseUrl,
                'key' => substr(self::$supabaseKey, 0, 10) . '...',
                'bucket' => self::$bucketName
            ]);
            throw new \Exception('Supabase configuration missing');
        }
    }

    public static function upload(UploadedFile $file, string $folder = 'general'): string
    {
        self::init();
        try {
            Log::info('Starting Supabase upload', [
                'file' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
                'folder' => $folder
            ]);

            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $path = rtrim($folder, '/') . '/' . $filename;
            $fileContent = file_get_contents($file->getRealPath());

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . self::$supabaseKey,
                'Content-Type' => $file->getMimeType(),
            ])->put(
                self::$supabaseUrl . '/storage/v1/object/' . self::$bucketName . '/' . $path,
                $fileContent
            );

            if ($response->successful()) {
                $publicUrl = self::$supabaseUrl . '/storage/v1/object/public/' . self::$bucketName . '/' . $path;
                Log::info('File uploaded to Supabase', ['path' => $path, 'url' => $publicUrl]);
                return $publicUrl;
            }

            Log::error('Supabase upload failed', [
                'status' => $response->status(),
                'response' => $response->body(),
                'file' => $file->getClientOriginalName()
            ]);
            throw new \Exception('Upload failed: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Supabase upload error', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public static function uploadText(string $content, string $path, string $contentType = 'application/json'): string
    {
        self::init();
        try {
            Log::info('Starting Supabase text upload', ['path' => $path, 'size' => strlen($content)]);
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . self::$supabaseKey,
                'Content-Type' => $contentType,
            ])->put(
                self::$supabaseUrl . '/storage/v1/object/' . self::$bucketName . '/' . $path,
                $content
            );

            if ($response->successful()) {
                $publicUrl = self::$supabaseUrl . '/storage/v1/object/public/' . self::$bucketName . '/' . $path;
                Log::info('Text uploaded to Supabase', ['path' => $path, 'url' => $publicUrl]);
                return $publicUrl;
            }

            Log::error('Supabase text upload failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            throw new \Exception('Text upload failed: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Supabase text upload error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}