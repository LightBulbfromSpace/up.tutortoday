<?php

namespace Up\Tutortoday\Controller;

use Up\Tutortoday\Services\UserService;

class MainPageController
{
    public static function getTutorsByPage(int $page = 1, array $filters = [])
    {

        //TODO: Filters handling

        $tutors = UserService::getUsersByPage($page);
        if ($tutors === false)
        {
            //TODO: Error handling
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
}