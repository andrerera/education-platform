<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseUploader
{
    private static $supabaseUrl;
    private static $supabaseKey;
    private static $bucketName;

    public static function init()
    {
        self::$supabaseUrl = config('services.supabase.url');
        self::$supabaseKey = config('services.supabase.service_role_key');
        self::$bucketName = config('services.supabase.bucket', 'edufiles');
    }

    /**
     * Upload file to Supabase Storage
     */
    public static function upload(UploadedFile $file, string $folder = 'general'): string
    {
        self::init();

        try {
            // Generate unique filename
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $path = $folder . '/' . $filename;

            // Get file content
            $fileContent = file_get_contents($file->getRealPath());
            
            // Upload to Supabase
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . self::$supabaseKey,
                'Content-Type' => $file->getMimeType(),
            ])->put(
                self::$supabaseUrl . '/storage/v1/object/' . self::$bucketName . '/' . $path,
                $fileContent
            );

            if ($response->successful()) {
                // Return public URL
                $publicUrl = self::$supabaseUrl . '/storage/v1/object/public/' . self::$bucketName . '/' . $path;
                
                Log::info('File uploaded to Supabase successfully', [
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType()
                ]);
                
                return $publicUrl;
            } else {
                Log::error('Supabase upload failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                throw new \Exception('Upload failed: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Supabase upload error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Upload text content to Supabase Storage
     */
    public static function uploadText(string $content, string $path, string $contentType = 'application/json'): string
    {
        self::init();

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . self::$supabaseKey,
                'Content-Type' => $contentType,
            ])->put(
                self::$supabaseUrl . '/storage/v1/object/' . self::$bucketName . '/' . $path,
                $content
            );

            if ($response->successful()) {
                $publicUrl = self::$supabaseUrl . '/storage/v1/object/public/' . self::$bucketName . '/' . $path;
                
                Log::info('Text content uploaded to Supabase successfully', [
                    'path' => $path,
                    'size' => strlen($content)
                ]);
                
                return $publicUrl;
            } else {
                throw new \Exception('Text upload failed: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Supabase text upload error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete file from Supabase Storage
     */
    public static function delete(string $path): bool
    {
        self::init();

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . self::$supabaseKey,
            ])->delete(
                self::$supabaseUrl . '/storage/v1/object/' . self::$bucketName . '/' . $path
            );

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('Supabase delete error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get file info from Supabase
     */
    public static function getFileInfo(string $path): ?array
    {
        self::init();

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . self::$supabaseKey,
            ])->get(
                self::$supabaseUrl . '/storage/v1/object/info/' . self::$bucketName . '/' . $path
            );

            if ($response->successful()) {
                return $response->json();
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Supabase get file info error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if file exists in Supabase
     */
    public static function exists(string $path): bool
    {
        return self::getFileInfo($path) !== null;
    }
}