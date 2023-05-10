<?php

namespace Up\Tutortoday\Model;

use Up\Tutortoday\Providers\EdFormatsProvider;
use Up\Tutortoday\Providers\LocationProvider;
use Up\Tutortoday\Providers\SubjectsProvider;
use Up\Tutortoday\Providers\UserRolesProvider;
use Up\Tutortoday\Services\EdFormatsService;
use Up\Tutortoday\Services\LocationService;

class Validator
{
    public static function validateEmail($email) : bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    public static function validatePassword(string $password, string $repeat = '') : string|bool
    {
        $password = trim($password);
        $repeat = trim($repeat);
        if (strlen($password) < 8 || strlen($password) > 100)
        {
            return 'pass_inval_len';
        }
        if ($repeat !== '' && $password !== $repeat)
        {
            return 'pass_match';
        }
        return true;
    }

    public static function validatePhoneNumber(string $phone) : bool
    {
        return preg_match('/^[+]?\d{4,25}$/', $phone);
    }

    public static function validateNameField(string $name, bool $required = true,  int $maxLen = 100, int $minLen = 1) : bool
    {
        $name = trim($name);
        if ($required === false && $name === '')
        {
            return true;
        }
        return (strlen($name) <= $maxLen && strlen($name) >= $minLen);
    }

    public static function validateSubjectsIDs(array $subjectsIDs, bool $required = true) : bool
    {
        if ($subjectsIDs === [])
        {
            return !$required;
        }
        $allSubjects = SubjectsProvider::getAllSubjects();
        $allSubjectsIDs = [];
        foreach ($allSubjects as $subject) {
            $allSubjectsIDs[] = $subject->getID();
        }
        foreach ($subjectsIDs as $subjectID)
        {
            if (!in_array($subjectID, $allSubjectsIDs))
            {
                return false;
            }
        }
        return true;
    }

    public static function validateEducationFormatIDs(array $edFormatsIDs, bool $required = true) : bool
    {
        if ($edFormatsIDs === [])
        {
            return !$required;
        }

        $allEdFormats = EdFormatsProvider::getAllEdFormats();
        $allEdFormatsIDs = [];
        foreach ($allEdFormats as $edFormat) {
            $allEdFormatsIDs[] = $edFormat->getID();
        }
        foreach ($edFormatsIDs as $edFormatID)
        {
            if (!in_array($edFormatID, $allEdFormatsIDs))
            {
                return false;
            }
        }
        return true;
    }

    public static function validateCitiesIDs(array $citiesIDs, bool $required = true) : bool
    {
        if ($citiesIDs === [])
        {
            return !$required;
        }
        $allCities = LocationProvider::getAllCities();
        $allCitiesIDs = [];
        foreach ($allCities as $city) {
            $allCitiesIDs[] = $city->getID();
        }
        foreach ($citiesIDs as $cityID)
        {
            if (!in_array($cityID, $allCitiesIDs))
            {
                return false;
            }
        }
        return true;
    }

    public static function validateRole(?int $RoleID)
    {
        if ($RoleID === null)
        {
            return false;
        }
        $roles = UserRolesProvider::getAllRoles();
        foreach ($roles as $role)
        {
            if ((int)$role['ID'] === $RoleID)
            {
                return true;
            }
        }
        return false;
    }
}