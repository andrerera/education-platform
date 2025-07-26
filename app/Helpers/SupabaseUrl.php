<?php
namespace App\Helpers;

class SupabaseUrl
{
    public static function getPublicUrl($path)
    {
        // If $path is a full URL, return it unchanged
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        $baseUrl = rtrim(env('SUPABASE_URL'), '/');
        $bucket = env('SUPABASE_BUCKET', 'edufiles'); // Use 'edufiles' as default
        $path = ltrim($path, '/');
        return "{$baseUrl}/storage/v1/object/public/{$bucket}/{$path}";
    }
}