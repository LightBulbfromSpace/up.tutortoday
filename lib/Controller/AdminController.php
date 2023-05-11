<?php

namespace Up\Tutortoday\Controller;

use Bitrix\Main\Type\ParameterDictionary;
use Up\Tutortoday\Model\FormObjects\CityForm;
use Up\Tutortoday\Model\FormObjects\EdFormatForm;
use Up\Tutortoday\Model\FormObjects\SubjectForm;
use Up\Tutortoday\Model\FormObjects\UserForm;
use Up\Tutortoday\Providers\EdFormatsProvider;
use Up\Tutortoday\Providers\LocationProvider;
use Up\Tutortoday\Providers\SubjectsProvider;
use Up\Tutortoday\Providers\UserProvider;
use Up\Tutortoday\Services\EdFormatsService;
use Up\Tutortoday\Services\ErrorService;
use Up\Tutortoday\Services\LocationService;
use Up\Tutortoday\Services\SubjectsService;
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
        $role = (new UserProvider($this->userID))->getUserRoleByID();
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

        $service = (new UserProvider($this->userID));
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
                'ROLE' => $item['ROLE'],
                'BLOCKED' => $item['blocked'],
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

        $result = SubjectsProvider::getSubjectsPerPage((int)$dict['page'], (int)$dict['itemsPerPage']);
        $subjects = [];
        foreach ($result as $item)
        {
            $subjects[] = ['ID' => $item['ID'], 'NAME' => $item['NAME']];
        }

        $result = [];
        $result['items'] = $subjects;

        $result['total'] = SubjectsProvider::getNumberOfAllSubjects();

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

        $result = EdFormatsProvider::getEdFormatsPerPage((int)$dict['page'], (int)$dict['itemsPerPage']);
        $edFormats = [];
        foreach ($result as $item)
        {
            $edFormats[] = ['ID' => $item['ID'], 'NAME' => $item['NAME']];
        }

        $result = [];
        $result['items'] = $edFormats;

        $result['total'] = EdFormatsProvider::getNumberOfAllEdFormats();

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

        $result = LocationProvider::getCitiesPerPage((int)$dict['page'], (int)$dict['itemsPerPage']);
        $cities = [];
        foreach ($result as $item)
        {
            $cities[] = ['ID' => $item['ID'], 'NAME' => $item['NAME']];
        }

        $result = [];
        $result['items'] = $cities;

        $result['total'] = LocationProvider::getNumberOfAllCities();

        return $result;
    }

    public function addSubject(ParameterDictionary $dict)
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }

        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        if (trim($dict['name']) === '')
        {
            return (new ErrorService('empty_field'))->getLastError();
        }

        $result = (new SubjectsService(new SubjectForm(null, $dict['name'])))->addNewEntity();
        if ($result !== null)
        {
            return (new ErrorService('ok'))->getLastError();
        }

        return (new ErrorService('add_err'))->getLastError();
    }

    public function addEdFormat(ParameterDictionary $dict)
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }

        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        if (trim($dict['name']) === '')
        {
            return (new ErrorService('empty_field'))->getLastError();
        }

        $result = (new EdFormatsService(new EdFormatForm(null, $dict['name'])))->addNewEntity();
        if ($result !== null)
        {
            return (new ErrorService('ok'))->getLastError();
        }

        return (new ErrorService('add_err'))->getLastError();
    }

    public function addCity(ParameterDictionary $dict)
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }

        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        if (trim((string)$dict['name']) === '')
        {
            return (new ErrorService('empty_field'))->getLastError();
        }

        $result = (new LocationService(new CityForm(null, $dict['name'])))->addNewEntity();
        if ($result !== null)
        {
            return (new ErrorService('ok'))->getLastError();
        }

        return (new ErrorService('add_err'))->getLastError();
    }

    public function deleteCity(ParameterDictionary $dict) : array
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }

        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }
        if ($dict['ID'] === null || !is_numeric($dict['ID']))
        {
            return (new ErrorService('del_err'))->getLastError();
        }

        $result = (new LocationService(new CityForm((int)$dict['ID'])))->deleteEntity();
        if ($result)
        {
            return (new ErrorService('ok'))->getLastError();
        }

        return (new ErrorService('del_err'))->getLastError();
    }

    public function deleteSubject(ParameterDictionary $dict)
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }
        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        return json_encode((new SubjectsService(new SubjectForm((int)$dict['ID'])))->deleteEntity());
    }

    public function deleteEdFormat(ParameterDictionary $dict)
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }
        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        return json_encode((new EdFormatsService(new EdFormatForm((int)$dict['ID'])))->deleteEntity());
    }

    public function editSubject(ParameterDictionary $dict)
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }

        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        if (trim($dict['name']) === '')
        {
            return (new ErrorService('empty_field'))->getLastError();
        }

        $result = (new SubjectsService(new SubjectForm((int)$dict['ID'], $dict['name'])))->editEntity();

        if ($result !== null)
        {
            return (new ErrorService('ok'))->getLastError();
        }

        return (new ErrorService('edit_err'))->getLastError();
    }

    public function editEdFormat(ParameterDictionary $dict)
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }
        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        if (trim($dict['name']) === '')
        {
            return (new ErrorService('empty_field'))->getLastError();
        }

        $result = (new EdFormatsService(new EdFormatForm((int)$dict['ID'], $dict['name'])))->editEntity();

        if ($result !== null)
        {
            return (new ErrorService('ok'))->getLastError();
        }

        return (new ErrorService('edit_err'))->getLastError();
    }

    public function editCity(ParameterDictionary $dict)
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }
        if (!$this->isAdmin())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }

        if (trim((string)$dict['name']) === '')
        {
            return (new ErrorService('empty_field'))->getLastError();
        }
        if ($dict['ID'] === null || !is_numeric($dict['ID']))
        {
            return (new ErrorService('edit_err'))->getLastError();
        }


        $result = (new LocationService(new CityForm((int)$dict['ID'], $dict['name'])))->editEntity();

        if ($result !== null)
        {
            return (new ErrorService('ok'))->getLastError();
        }

        return (new ErrorService('edit_err'))->getLastError();
    }

    public function setUserBlockInfo(ParameterDictionary $dict)
    {
        if (!$this->isAdmin() || !check_bitrix_sessid())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }
        return (new UserService(new UserForm($this->userID)))->setBlockStatus((int)$dict['userID'], $dict['blocked']);
    }
}