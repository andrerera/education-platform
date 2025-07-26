<?php

namespace App\Helpers;

class SupabaseUrl
{
    public static function getPublicUrl($path)
    {
        $baseUrl = rtrim(env('SUPABASE_URL'), '/') . '/storage/v1/object/public/';
        return $baseUrl . ltrim($path, '/');
    }
}
