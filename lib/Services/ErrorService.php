<?php

namespace Up\Tutortoday\Services;

class ErrorService
{
    private array $errorCodes;

    public function __construct(string $errCode = '')
    {
        if ($errCode === '')
        {
            $this->errorCodes = [];
            return;
        }
        $this->errorCodes = [$errCode];
    }

    public function getLastErrorText() : string
    {
        return match ($this->errorCodes[count($this->errorCodes)-1]) {
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
            'not_img' => "File is not an image",
            'ok' => "Success",
            default => "Undefined error",
        };
    }

    public function getLastError() : array
    {
        return $this->getErrorByIndex(count($this->errorCodes)-1);
    }

    protected function getErrorByIndex(int $i) : array
    {
        return [
            'TYPE' => $this->errorCodes[$i] === 'ok' ? 'OK' : 'ERROR',
            'MESSAGE' => $this->getLastErrorText(),
        ];
    }

    public function addError(string $errorCode) : void
    {
        $this->errorCodes[] = $errorCode;
    }

    public function isNoErrors() : bool
    {
        foreach ($this->errorCodes as $err)
        {
            if ($err !== 'ok')
            {
                return false;
            }
        }

        return true;
    }

    public function  getAllErrors() : array
    {
        $errors = [];
        $errCodesLen = count($this->errorCodes);
        for ($i = 0; $i < $errCodesLen; $i++)
        {
            $errors[] = $this->getErrorByIndex($i);
        }
        return $errors;
    }
}