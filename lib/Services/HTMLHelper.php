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

        if (strlen(mb_substr($text, 0, 1, 'UTF-8')) === 2) {
            $maxLength *= 1.7;
        }

        return mb_strlen($text) > $maxLength - 3 ?
            mb_strcut($text, 0, $maxLength) . '...' :
            $text;
    }
}