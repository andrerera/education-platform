<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class SupabaseUploader
{
    public static function upload($file, $bucket, $path)
    {
        $supabaseUrl = env('SUPABASE_URL');
        $supabaseKey = env('SUPABASE_KEY');

        $fileContents = file_get_contents($file->getRealPath());

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$supabaseKey}",
            'Content-Type' => $file->getMimeType(),
        ])->withBody($fileContents, $file->getMimeType())
          ->put("{$supabaseUrl}/storage/v1/object/{$bucket}/{$path}");

        if ($response->successful()) {
            return "{$supabaseUrl}/storage/v1/object/public/{$bucket}/{$path}";
        }

        throw new \Exception("Upload to Supabase failed: " . $response->body());
    }
}
