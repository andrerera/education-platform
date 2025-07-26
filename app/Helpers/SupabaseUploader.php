<?php

use GuzzleHttp\Psr7\Utils;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SupabaseUploader
{
    public static function upload($file, $folder)
    {
        $supabaseUrl = config('services.supabase.url');
        $supabaseKey = config('services.supabase.key');
        $bucket = 'edufiles';

        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = "$folder/$fileName";

        $fileStream = Utils::streamFor(fopen($file->getRealPath(), 'r'));

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$supabaseKey}",
            'Content-Type' => $file->getMimeType(),
        ])->withBody($fileStream, $file->getMimeType())
          ->put("{$supabaseUrl}/storage/v1/object/$bucket/$path");

        if ($response->successful()) {
            return "{$supabaseUrl}/storage/v1/object/public/{$bucket}/{$path}";
        }

        throw new \Exception("Upload to Supabase failed: " . $response->body());
    }
}