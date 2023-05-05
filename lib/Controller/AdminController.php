<?php

namespace Up\Tutortoday\Controller;

use Bitrix\Main\Type\ParameterDictionary;
use Up\Tutortoday\Services\EducationService;
use Up\Tutortoday\Services\ErrorService;
use Up\Tutortoday\Services\LocationService;
use Up\Tutortoday\Services\UserService;

class AdminController
{
    private int $userID;

    public function __construct($userID)
    {
        $this->userID = $userID;
    }

    public function isAdmin() : bool
    {
        $role = (new UserService($this->userID))->getUserRoleByID();
        return $role['NAME'] === 'administrator';
    }

    public function getSubjects(ParameterDictionary $dict)
    {
        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        $result = EducationService::getSubjectsPerPage((int)$dict['page'], (int)$dict['itemsPerPage']);
        $subjects = [];
        foreach ($result as $item)
        {
            $subjects[] = ['ID' => $item['ID'], 'NAME' => $item['NAME']];
        }

        return $subjects;
    }

    public function getEdFormats(ParameterDictionary $dict) : ?array
    {
        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        $result = EducationService::getEdFormatsPerPage((int)$dict['page'], (int)$dict['itemsPerPage']);
        $edFormats = [];
        foreach ($result as $item)
        {
            $edFormats[] = ['ID' => $item['ID'], 'NAME' => $item['NAME']];
        }

        return $edFormats;
    }

    public function getCities(ParameterDictionary $dict)
    {
        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        $result = LocationService::getCitiesPerPage((int)$dict['page'], (int)$dict['itemsPerPage']);
        $cities = [];
        foreach ($result as $item)
        {
            $cities[] = ['ID' => $item['ID'], 'NAME' => $item['NAME']];
        }

        return $cities;
    }

    public function addSubject(ParameterDictionary $dict)
    {
        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        return json_encode(EducationService::addNewSubject($dict['name']));
    }

    public function addEdFormat(ParameterDictionary $dict)
    {
        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        return json_encode(EducationService::addNewEdFormat($dict['name']));
    }

    public function addCity(ParameterDictionary $dict)
    {
        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        return json_encode(EducationService::addNewCity($dict['name']));
    }
}