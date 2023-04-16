<?php

namespace Up\Tutortoday\Model;

use Up\Tutortoday\Services\EducationService;

class Validator
{
    public static function validateEmail($email) : bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    public static function validatePassword(string $password, string $repeat = null) : string|bool
    {
        if (strlen($password) < 8 || strlen($password) > 100)
        {
            return 'pass_too_short';
        }
        if ($repeat !== null && $password !== $repeat)
        {
            return 'pass_match';
        }
        return true;
    }

    public static function validatePhoneNumber(string $phone) : bool
    {
        $phoneNumbers = preg_replace('/\D/', '', $phone);
        return (strlen($phoneNumbers) <= 13 && strlen($phoneNumbers) >= 10);
    }

    public static function validateNameField(string $name, bool $required = true,  int $maxLen = 100, int $minLen = 1) : bool
    {
        if ($required === false && $name === '')
        {
            return true;
        }
        return (strlen($name) <= $maxLen && strlen($name) >= $minLen);
    }

    public static function validateSubjectID(int $ID, bool $required = true) : bool
    {
        if (!$required && $ID === 0)
        {
            return true;
        }
        return !(EducationService::getSubjectByID($ID) === null);
    }

    public static function validateEducationFormatID(int $ID, bool $required = true) : bool
    {
        if (!$required && $ID === 0)
        {
            return true;
        }
        return !(EducationService::getEducationFormatByID($ID) === null);
    }
}