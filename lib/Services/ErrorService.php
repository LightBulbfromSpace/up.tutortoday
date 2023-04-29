<?php

namespace Up\Tutortoday\Services;

class ErrorService
{
    private string $code;

    public function __construct(string $errCode)
    {
        $this->code = $errCode;
    }

    public function getErrorTextByGetCode() : string
    {
        return match ($this->code) {
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
            'invalid_city' => "Invalid city",
            'invalid_csrf' => "Invalid CSRF token",
            'perm_denied' => "Permission denied",
            'ok' => 'Success',
            default => "Undefined error",
        };
    }

    public function getMessage()
    {
        return [
            'TYPE' => $this->code === 'ok' ? 'OK' : 'ERROR',
            'MESSAGE' => $this->getErrorTextByGetCode(),
        ];
    }
}