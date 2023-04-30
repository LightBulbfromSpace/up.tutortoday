<?php

namespace Up\Tutortoday\Controller;

use Bitrix\Main\Type\ParameterDictionary;
use Bitrix\Main\UserTable;
use Up\Tutortoday\Services\DatetimeService;
use Up\Tutortoday\Services\FiltersService;
use Up\Tutortoday\Services\SearchService;
use Up\Tutortoday\Services\UserService;

class MainPageController
{
    private array $rolesIDs = [1];
    private int $numberOfUsers;


    public function getNumberOfUsers(): int
    {
        return $this->numberOfUsers;
    }

    /**
     * @param int[] $roleIDs
     */
    public function setRolesByIDs(array $roleIDs): void
    {
        $this->rolesIDs = $roleIDs;
    }

    /**
     * @param string[] $roleIDs
     */
    public function setRolesByNames(array $roleIDs): void
    {
        $this->rolesIDs = $roleIDs;
    }
    public function getTutorsByPage(int $pageFromNull = 0, array $filters = null, $search = null) : array
    {
        $result = [];
        $areAllUsers = true;
        if ($search !== null)
        {
            $searchService = new SearchService($search);
            $result = $searchService->generalSearch();
            $this->numberOfUsers = $searchService->getNumberOfUsers();
            $areAllUsers = false;
        }
        else if (count($filters) !== 0)
        {
            $filter = new FiltersService($filters);
            $result = $filter->filterTutors();
            $this->numberOfUsers = $filter->getNumberOfFilteredUsers();
            $areAllUsers = false;
        }

        $userService = new UserService(0, $result);
        $userService->setRoles(['tutor']);
        $userService->setFetchAllAvailableUsers($areAllUsers);

        $tutors = $userService->getUsersByPage($pageFromNull * USERS_BY_PAGE, USERS_BY_PAGE);

        $this->numberOfUsers = $userService->getNumOfFetchedUsers();

        if ($tutors === false)
        {
            //TODO: Error handling
        }

        return $tutors;
    }
}