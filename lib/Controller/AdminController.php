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

    public function getUsers(ParameterDictionary $dict)
    {
        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        $offset = (int)$dict['page'] * (int)$dict['itemsPerPage'];

        if ((int)$dict['page'] < 0 || (int)$dict['itemsPerPage'] < 1)
        {
            return (new ErrorService('invalid_params'))->getLastError();
        }

        $service = (new UserService($this->userID));
        $service->setRoles(['student', 'tutor']);
        $service->setFetchAllAvailableUsers(true);
        $result = $service->getUsersByPage($offset, (int)$dict['itemsPerPage'], true);
        $users = [];
        foreach ($result as $item)
        {
            $users[] = [
                'ID' => $item['ID'],
                'NAME' => $item['fullName']['name'],
                'LAST_NAME' => $item['fullName']['lastName'],
                'SECOND_NAME' => $item['fullName']['secondName'],
                'ROLE' => $item['ROLE']
            ];
        }

        $result = [];
        $result['items'] = $users;
        $result['total'] = $service->getNumOfFetchedUsers();

        return $result;
    }

    public function getSubjects(ParameterDictionary $dict)
    {
        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        if ((int)$dict['page'] < 0 || (int)$dict['itemsPerPage'] < 1)
        {
            return (new ErrorService('invalid_params'))->getLastError();
        }

        $result = EducationService::getSubjectsPerPage((int)$dict['page'], (int)$dict['itemsPerPage']);
        $subjects = [];
        foreach ($result as $item)
        {
            $subjects[] = ['ID' => $item['ID'], 'NAME' => $item['NAME']];
        }

        $result = [];
        $result['items'] = $subjects;

        $result['total'] = EducationService::getNumberOfAllSubjects();

        return $result;
    }

    public function getEdFormats(ParameterDictionary $dict) : ?array
    {
        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        if ((int)$dict['page'] < 0 || (int)$dict['itemsPerPage'] < 1)
        {
            return (new ErrorService('invalid_params'))->getLastError();
        }

        $result = EducationService::getEdFormatsPerPage((int)$dict['page'], (int)$dict['itemsPerPage']);
        $edFormats = [];
        foreach ($result as $item)
        {
            $edFormats[] = ['ID' => $item['ID'], 'NAME' => $item['NAME']];
        }

        $result = [];
        $result['items'] = $edFormats;

        $result['total'] = EducationService::getNumberOfAllEdFormats();

        return $result;
    }

    public function getCities(ParameterDictionary $dict)
    {
        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        if ((int)$dict['page'] < 0 || (int)$dict['itemsPerPage'] < 1)
        {
            return (new ErrorService('invalid_params'))->getLastError();
        }

        $result = LocationService::getCitiesPerPage((int)$dict['page'], (int)$dict['itemsPerPage']);
        $cities = [];
        foreach ($result as $item)
        {
            $cities[] = ['ID' => $item['ID'], 'NAME' => $item['NAME']];
        }

        $result = [];
        $result['items'] = $cities;

        $result['total'] = LocationService::getNumberOfAllCities();

        return $result;
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

    public function setUserBlockInfo(ParameterDictionary $dict)
    {
        if (!$this->isAdmin() || !check_bitrix_sessid())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }
        return (new UserService($this->userID))->setBlockStatus((int)$dict['userID'], $dict['blocked']);
    }
}