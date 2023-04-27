<?php

namespace Up\Tutortoday\Services;

class HTMLHelper
{
    public static function cutText(?string $text, int $maxLength = 200) : ?string
    {
        if ($text == null)
        {
            return null;
        }
        return mb_strlen($text) > $maxLength - 3 ?
            mb_strcut($text, 0, $maxLength) . '...' :
            $text;
    }
}