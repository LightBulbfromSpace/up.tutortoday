<?php

namespace Up\Tutortoday\Controller;

use Bitrix\Main\Type\ParameterDictionary;
use Up\Tutortoday\Services\DatetimeService;
use Up\Tutortoday\Services\FiltersService;
use Up\Tutortoday\Services\UserService;

class MainPageController
{
    public static function getTutorsByPage(int $page = 1, ParameterDictionary $filters = null) : array
    {
        $page--;
        if ($filters->count() !== 0)
        {
            $filter = new FiltersService($filters);
            $filter->filterTutors($page, USERS_BY_PAGE);
            $tutors = $filter->getFilteredTutors();
        }
        else
        {
            $tutors = UserService::getUsersByPage($page);
            if ($tutors === false)
            {
                //TODO: Error handling
            }
        }

        return $tutors;
    }

    public static function getNumberOfPages() : int
    {
        return 1;
//        return \CUser::getList('', '', [
//            'WORK_COMPANY' => 'TutorToday',
//        ])->GetCount();
    }

//	public static function getTutorsByName(ParameterDictionary $post)
//	{
//		if(!check_bitrix_sessid())
//		{
//			return null;
//		}
//		return FiltersService::getTutorsByName($post['NAME']);
//	}

	public static function getTutorsByFilters(ParameterDictionary $post)
	{
		if(!check_bitrix_sessid())
		{
			return null;
		}
        $filter = new FiltersService;
        $filter->getTutorsByFilters();
		return $filter;
	}
}