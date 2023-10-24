<?php

namespace AmidEsfahani\Helpers;

class HumanReadable
{
    /**
     * $translate requires Laravel
     */
    public static function FileSize($bytes, $translate = false, $round = 0)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, $round) . ' ' . ($translate ? __($units[$i]) : $units[$i]);
    }

    public static function roundDown($n, $increment)
    {
        return floor($n / $increment) * $increment;
    }
}

if (!function_exists('__'))
{
    function __($phrase)
    {
        return $phrase;
    }
}