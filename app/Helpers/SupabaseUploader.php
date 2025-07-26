<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SupabaseUploader
{
    public static function upload($file, $folder)
    {
        $supabaseUrl = env('SUPABASE_URL');
        $supabaseKey = env('SUPABASE_SERVICE_KEY'); // Gunakan SERVICE KEY
        $bucket = 'edufiles'; // Tetap

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $folder . '/' . $filename;

        $fileContents = file_get_contents($file->getRealPath());

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$supabaseKey}",
            'Content-Type' => $file->getMimeType(),
        ])->put("{$supabaseUrl}/storage/v1/object/{$bucket}/{$path}", $fileContents);

        if ($response->successful()) {
            return "{$supabaseUrl}/storage/v1/object/public/{$bucket}/{$path}";
        }

        throw new \Exception("Upload to Supabase failed: " . $response->body());
    }
}