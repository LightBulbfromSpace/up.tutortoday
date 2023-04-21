<?php

namespace Up\Tutortoday\Controller;

use Bitrix\Main\Type\ParameterDictionary;
use Up\Tutortoday\Model\Tables\ContactsTable;
use Up\Tutortoday\Model\Tables\UserTable;
use Up\Tutortoday\Services\ImagesService;
use Up\Tutortoday\Services\DatetimeService;
use Up\Tutortoday\Services\UserService;

class ProfileController
{

    public static function isOwnerOfProfile($userID) : bool
    {
        if (!session()->has('userID'))
        {
            return false;
        }

        if (session()->get('userID') !== $userID)
        {
            return false;
        }

        return true;
    }

    public static function getUserTimeByDayID(ParameterDictionary $post)
    {
        if(!check_bitrix_sessid())
        {
            return null;
        }
        return DatetimeService::getWeekdayTimeByUserID($post['userID'], $post['weekdayID']);
    }
}