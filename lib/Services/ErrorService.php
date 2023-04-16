<?php

namespace Up\Tutortoday\Services;

class ErrorService
{
    public static function getErrorTextByGetCode($code) : string
    {
        return match ($code) {
            'empty_field' => "Required fields can't be empty",
            'auth' => "Invalid login or password",
            'pass_too_short' => "Password is too short",
            'pass_match' => "Password's repetition doesn't match",
            'invalid_email' => "E-mail is invalid",
            'user_exists' => "User already exists",
            'invalid_phone' => "Phone number is invalid",
            'invalid_subject' => "Subject is invalid",
            'invalid_ed_format' => "Education format is invalid",
            'unexpected_error' => "Unexpected error",
            default => '',
        };
    }
}