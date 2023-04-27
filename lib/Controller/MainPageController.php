<?php

namespace Up\Tutortoday\Controller;

use Bitrix\Main\Type\ParameterDictionary;
use Bitrix\Main\UserTable;
use Up\Tutortoday\Services\DatetimeService;
use Up\Tutortoday\Services\FiltersService;
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
    public function getTutorsByPage(int $pageFromNull = 0, array $filters = null) : array
    {

        if (count($filters) !== 0)
        {
            $filter = new FiltersService($filters);
            $filter->filterTutors($pageFromNull, USERS_BY_PAGE);

            $this->numberOfUsers = $filter->getNumberOfFilteredUsers();

            return $filter->getFilteredTutors();
        }
        $service = new UserService();
        $service->setRoles($this->rolesIDs);
        $service->setFetchAllAvailableUsers(true);

        $tutors = $service->getUsersByPage($pageFromNull, USERS_BY_PAGE);

        $this->numberOfUsers = $service->getNumberOfAllAvailableUsers();

        if ($tutors === false)
        {
            //TODO: Error handling
        }

        return $tutors;
    }


//	public static function getTutorsByFilters(ParameterDictionary $post)
//	{
//		if(!check_bitrix_sessid())
//		{
//			return null;
//		}
//        $filter = new FiltersService($post);
//        $filter->getTutorsByFilters();
//		return $filter;
//	}
}