<?php

namespace Up\Tutortoday\Controller;

use Bitrix\Main\Type\ParameterDictionary;
use Up\Tutortoday\Model\FormObjects\UserForm;
use Up\Tutortoday\Model\FormObjects\UserRegisterForm;
use Up\Tutortoday\Services\EducationService;
use Up\Tutortoday\Services\ErrorService;
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

    public function updatePassword(ParameterDictionary $post)
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }
        if (!$this->isOwnerOfProfile())
        {
            return (new ErrorService('perm_denied'))->getLastError();
        }
        if ($post['newPassword'] == '' || $post['passwordConfirm'] == '' || $post['oldPassword'] == '')
        {
            return (new ErrorService('empty_field'))->getLastError();
        }

        $result = (new UserService($this->userID))
            ->UpdatePassword($post['oldPassword'], $post['newPassword'], $post['passwordConfirm']);
        if ($result['TYPE'] === 'OK')
        {
            return (new ErrorService('ok'))->getLastErrorText();
        }
        return $result;
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

    public function deleteProfile()
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }
        if(!$this->isOwnerOfProfile())
        {
            ShowMessage('permitted: not owner');
        }
        (new UserService($this->userID))->deleteUser();

        LocalRedirect('/');
    }

    public static function createTime(ParameterDictionary $post)
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }
        if($post['timeFrom'] === null || $post['timeTo'] === null)
        {
            return null;
        }
        $timeToAdd = [
            'timeFrom' => $post['timeFrom'],
            'timeTo' => $post['timeTo'],
        ];
        return DatetimeService::createTime((int)$post['userID'], (int)$post['weekdayID'], $timeToAdd);
    }

    public static function deleteTime(ParameterDictionary $post)
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }
        return DatetimeService::deleteTime($post['timeID']);
    }

    public static function getUserTimeByDayID(ParameterDictionary $post)
    {
        return DatetimeService::getWeekdayTimeByUserID($post['userID'], (int)$post['weekdayID']);
    }

    public function updateUser()
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }
        if (!$this->isOwnerOfProfile())
        {
            LocalRedirect("/profile/$this->userID/");
            return;
        }
        $user = new UserRegisterForm(getPostList());
        (new UserService($this->userID))->UpdateUser($user);

        LocalRedirect("/profile/$this->userID/");
    }

    public static function deleteSubject(ParameterDictionary $post)
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }
        (new EducationService([$post['userID']]))->deleteSubject($post['subjectID']);
    }

    public static function getAllSubjectsJSON()
    {
        $subjectsRaw = EducationService::getAllSubjects();
        $subjects = [];
        foreach ($subjectsRaw as $subject)
        {
            $subjects[] = [
              'ID' => $subject['ID'],
              'name' => $subject['NAME'],
            ];
        }
        return $subjects;
    }

    public function updatePhotoTmp(array $photo)
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }
        $result = (new ImagesService($this->userID))->saveImageToStorage($photo);
        if ($result === null)
        {
            return (new ErrorService($result))->getLastError();
        }
        return $result;
    }

    public function updateProfilePhoto()
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }
        return (new UserService($this->userID))->saveProfilePhoto();
    }

    public function getProfilePhoto()
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }
        $result = (new ImagesService($this->userID))->getProfileImage();
        return $result == null ? DEFAULT_PHOTO : $result['LINK'];
    }

    public function cancelProfilePhotoUpdate()
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }
        $service = (new ImagesService($this->userID));
        $service->clearTrash($service->getTmpDir());
        return true;
    }

    public function deleteProfilePhoto()
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }
        return (new ImagesService($this->userID))->deleteProfilePhoto();
    }
}