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

        if (trim($dict['name']) === '')
        {
            return (new ErrorService('empty_field'))->getLastError();
        }

        $result = EducationService::addNewSubject($dict['name']);
        if ($result === true)
        {
            return (new ErrorService('ok'))->getLastError();
        }
        return json_encode($result);
    }

    public function addEdFormat(ParameterDictionary $dict)
    {
        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        if (trim($dict['name']) === '')
        {
            return (new ErrorService('empty_field'))->getLastError();
        }

        $result = EducationService::addNewEdFormat($dict['name']);
        if ($result === true)
        {
            return (new ErrorService('ok'))->getLastError();
        }

        return json_encode($result);
    }

    public function addCity(ParameterDictionary $dict)
    {
        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        if (trim($dict['name']) === '')
        {
            return (new ErrorService('empty_field'))->getLastError();
        }

        $result = LocationService::addNewCity($dict['name']);
        if ($result === true)
        {
            return (new ErrorService('ok'))->getLastError();
        }

        return json_encode($result);
    }

    public function deleteCity(ParameterDictionary $dict)
    {
        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        return json_encode(LocationService::deleteCity((int)$dict['ID']));
    }

    public function deleteSubject(ParameterDictionary $dict)
    {
        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        return json_encode(EducationService::deleteSubject((int)$dict['ID']));
    }

    public function deleteEdFormat(ParameterDictionary $dict)
    {
        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        return json_encode(EducationService::deleteEdFormat((int)$dict['ID']));
    }

    public function editSubject(ParameterDictionary $dict)
    {
        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        if (trim($dict['name']) === '')
        {
            return (new ErrorService('empty_field'))->getLastError();
        }

        $result = EducationService::editSubject((int)$dict['ID'], $dict['name']);

        if ($result === true)
        {
            return (new ErrorService('ok'))->getLastError();
        }

        return json_encode($result);
    }

    public function editEdFormat(ParameterDictionary $dict)
    {
        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        if (trim($dict['name']) === '')
        {
            return (new ErrorService('empty_field'))->getLastError();
        }

        $result = EducationService::editEdFormat((int)$dict['ID'], $dict['name']);

        if ($result === true)
        {
            return (new ErrorService('ok'))->getLastError();
        }

        return json_encode($result);
    }

    public function editCity(ParameterDictionary $dict)
    {
        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        if (trim($dict['name']) === '')
        {
            return (new ErrorService('empty_field'))->getLastError();
        }

        $result = LocationService::editCity((int)$dict['ID'], $dict['name']);

        if ($result === true)
        {
            return (new ErrorService('ok'))->getLastError();
        }

        return json_encode($result);
    }
}