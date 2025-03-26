<?php

namespace App\Helpers;

class TextHelper
{
    public static function extractFirstWord($text)
    {
        preg_match('/^(\S+)/u', trim($text), $matches);
        return $matches[1] ?? $text; // Agar topilmasa, butun matnni qaytaradi
    }
}
