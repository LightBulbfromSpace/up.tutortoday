<?php

namespace Up\Tutortoday\Helpers;

class HTMLHelper
{
    public static function cutText(?string $text, int $maxLength = 200) : ?string
    {
        if ($text == null)
        {
            return null;
        }

        $charLen = strlen(mb_substr($text, 0, 1));

        return mb_strlen($text) > ($maxLength - 3) * $charLen ?
            mb_strcut($text, 0, $maxLength * $charLen) . '...' :
            $text;
    }
}