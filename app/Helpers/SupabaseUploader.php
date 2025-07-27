<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseUploader
{
    protected static string $supabaseUrl;
    protected static string $supabaseKey;
    protected static string $bucketName = 'edufiles';

    protected static function init(): void
    {
        self::$supabaseUrl = rtrim(config('services.supabase.url'), '/');
        self::$supabaseKey = config('services.supabase.key');
    }

    public static function upload(UploadedFile $file, string $folder = 'general'): string
    {
        self::init();

        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $path = $folder . '/' . $filename;

        $fileContent = file_get_contents($file->getRealPath());

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . self::$supabaseKey,
            'Content-Type' => $file->getMimeType(),
        ])->put(
            self::$supabaseUrl . '/storage/v1/object/' . self::$bucketName . '/' . $path,
            $fileContent
        );

        if ($response->successful()) {
            Log::info('File uploaded to Supabase successfully', [
                'path' => $path,
                'size' => $file->getSize(),
                'type' => $file->getMimeType()
            ]);

            return self::$supabaseUrl . '/storage/v1/object/public/' . self::$bucketName . '/' . $path;
        } else {
            Log::error('Supabase upload failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            throw new \Exception('Upload failed: ' . $response->body());
        }
    }

    public static function uploadText(string $content, string $path, string $contentType = 'application/json'): string
    {
        self::init();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . self::$supabaseKey,
            'Content-Type' => $contentType,
        ])->put(
            self::$supabaseUrl . '/storage/v1/object/' . self::$bucketName . '/' . $path,
            $content
        );

        if ($response->successful()) {
            Log::info('Text content uploaded to Supabase successfully', [
                'path' => $path,
                'size' => strlen($content)
            ]);

            return self::$supabaseUrl . '/storage/v1/object/public/' . self::$bucketName . '/' . $path;
        } else {
            throw new \Exception('Text upload failed: ' . $response->body());
        }
    }

    public static function delete(string $path): bool
    {
        self::init();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . self::$supabaseKey,
        ])->delete(
            self::$supabaseUrl . '/storage/v1/object/' . self::$bucketName . '/' . $path
        );

        return $response->successful();
    }

    public static function getFileInfo(string $path): ?array
    {
        self::init();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . self::$supabaseKey,
        ])->get(
            self::$supabaseUrl . '/storage/v1/object/info/' . self::$bucketName . '/' . $path
        );

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public static function exists(string $path): bool
    {
        return self::getFileInfo($path) !== null;
    }
}