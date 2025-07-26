<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class SupabaseUploader
{
    public static function upload($file, $path, $bucket = 'edufiles')
    {
        $supabaseUrl = env('SUPABASE_URL');
        $supabaseKey = env('SUPABASE_SERVICE_KEY'); // service_role key (private)
        $fileContents = file_get_contents($file->getRealPath());

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$supabaseKey}",
            'Content-Type' => $file->getMimeType(),
        ])->put("{$supabaseUrl}/storage/v1/object/$bucket/$path", $fileContents);

        if ($response->successful()) {
            return "{$supabaseUrl}/storage/v1/object/public/{$bucket}/{$path}";
        }

        return null;
    }
}