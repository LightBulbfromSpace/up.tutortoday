<?php

namespace Up\Tutortoday\Controller;

use Bitrix\Main\Type\ParameterDictionary;
use Up\Tutortoday\Model\FormObjects\UserForm;
use Up\Tutortoday\Model\FormObjects\UserRegisterForm;
use Up\Tutortoday\Model\Tables\UserTable;
use Up\Tutortoday\Services\EducationService;
use Up\Tutortoday\Services\ImagesService;
use Up\Tutortoday\Services\DatetimeService;
use Up\Tutortoday\Services\UserService;

class ProfileController
{

    private int $userID;

    public function __construct(int $userID)
    {
        $this->userID = $userID;
    }

    public function isOwnerOfProfile() : bool
    {
        global $USER;
        if ($USER->GetID() == null)
        {
            return false;
        }

        if ((int)$USER->GetID() !== $this->userID)
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

        $user = new UserRegisterForm(getPostList());
        (new UserService((int)$id))->UpdateUser($user);

        LocalRedirect("/profile/$id/");
    }

    public static function deleteSubject(ParameterDictionary $post)
    {
        if(!check_bitrix_sessid())
        {
            return null;
        }

        (new EducationService([$post['userID']]))->deleteSubject($post['subjectID']);
    }
}