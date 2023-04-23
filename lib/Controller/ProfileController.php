<?php

namespace Up\Tutortoday\Controller;

use Bitrix\Main\Type\ParameterDictionary;
use Up\Tutortoday\Model\FormObjects\UserForm;
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
        return DatetimeService::getWeekdayTimeByUserID($post['userID'], (int)$post['weekdayID']);
    }

    public static function updateUser($id)
    {
        if(!check_bitrix_sessid())
        {
            return null;
        }
        $post = getPostList();
        $user = new UserForm(
            $post['name'], $post['surname'], $post['education_format'],
            $post['middleName'], $post['description'], $post['city'],
        );
        (new UserService((int)$id))->UpdateUser($user);
        LocalRedirect("/profile/$id/");
    }
}