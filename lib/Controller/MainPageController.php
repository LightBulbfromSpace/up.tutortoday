<?php

namespace Up\Tutortoday\Controller;

use Bitrix\Main\Type\ParameterDictionary;
use Bitrix\Main\UserTable;
use Up\Tutortoday\Providers\UserProvider;
use Up\Tutortoday\Services\DatetimeService;
use Up\Tutortoday\Services\FiltersService;
use Up\Tutortoday\Providers\SearchProvider;
use Up\Tutortoday\Services\UserService;

class MainPageController
{
    private array $rolesIDs = [1];
    private int $numberOfUsers = 0;
    private int $pageFromNull = 0;

    /**
     * @param int $pageFromNull
     */
    public function __construct(int $pageFromNull = 0)
    {
        $this->pageFromNull = $pageFromNull;
    }


    public function setPageFromNull(int $pageFromNull): void
    {
        $this->pageFromNull = $pageFromNull;
    }


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

    public function getTutorsByPageByUserPreferences(int $userID) : array
    {
        $filters = (new UserProvider($userID))->getPreferences();

        return $this->getTutorsByPageByFilters($filters);
    }

    public function getTutorsByPageBySearch(string $search = '') : array
    {
        if ($search === '')
        {
            return [];
        }

        $searchService = new SearchProvider($search);
        $foundUsersIDs = $searchService->generalSearch();
        $this->numberOfUsers = $searchService->getNumberOfUsers();

        $userService = new UserProvider(0, $foundUsersIDs);
        $userService->setRoles(['tutor']);
        $userService->setFetchAllAvailableUsers(false);
        $userService->getOnlyUnblockedUsers(true);

        $tutors = $userService->getUsersByPage($this->pageFromNull * USERS_BY_PAGE, USERS_BY_PAGE);
        $this->numberOfUsers = $userService->getNumOfFetchedUsers();

        return $tutors;
    }

    public function getTutorsByPageByFilters(array $filters = []) : array
    {
        if (count($filters) === 0)
        {
            return [];
        }

        $filter = new FiltersService($filters);
        $filteredUsersIDs = $filter->filterTutors();
        $this->numberOfUsers = $filter->getNumberOfFilteredUsers();
        $userService = new UserProvider(0, $filteredUsersIDs);
        $userService->setRoles(['tutor']);
        $userService->setFetchAllAvailableUsers(false);
        $userService->getOnlyUnblockedUsers(true);

        $tutors = $userService->getUsersByPage($this->pageFromNull * USERS_BY_PAGE, USERS_BY_PAGE);
        $this->numberOfUsers = $userService->getNumOfFetchedUsers();

        return $tutors;
    }
    public function getTutorsByPage() : array
    {
        $userService = new UserProvider();
        $userService->setRoles(['tutor']);
        $userService->setFetchAllAvailableUsers(true);
        $userService->getOnlyUnblockedUsers(true);

        $tutors = $userService->getUsersByPage($this->pageFromNull * USERS_BY_PAGE, USERS_BY_PAGE);
        $this->numberOfUsers = $userService->getNumOfFetchedUsers();

        return $tutors;
    }
}