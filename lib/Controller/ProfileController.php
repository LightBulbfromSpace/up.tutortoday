<?php

namespace Up\Tutortoday\Controller;

use Bitrix\Main\Type\ParameterDictionary;
use Up\Tutortoday\Model\FormObjects\FeedbackForm;
use Up\Tutortoday\Model\FormObjects\UserForm;
use Up\Tutortoday\Model\FormObjects\UserRegisterForm;
use Up\Tutortoday\Model\Validator;
use Up\Tutortoday\Services\EducationService;
use Up\Tutortoday\Services\ErrorService;
use Up\Tutortoday\Services\FeedbackService;
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

        if ($post['newPassword'] == '' || $post['passwordConfirm'] == '' || $post['oldPassword'] == '')
        {
            return (new ErrorService('empty_field'))->getLastError();
        }

        return (new UserService($this->userID))
            ->UpdatePassword($post['oldPassword'], $post['newPassword'], $post['passwordConfirm']);
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
//        if(!$this->isOwnerOfProfile())
//        {
//            ShowMessage('permitted: not owner');
//        }
        (new UserService($this->userID))->deleteUser();

        LocalRedirect('/');
    }

    public function createTime(ParameterDictionary $post)
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }
        if($post['timeFrom'] === null || $post['timeTo'] === null)
        {
            return null;
        }

        $start = date_timestamp_get(new \DateTime($post['timeFrom']));
        $end = date_timestamp_get(new \DateTime($post['timeTo']));

        if (($end - $start) < 0)
        {
            return null;
        }

        $timeToAdd = [
            'timeFrom' => $post['timeFrom'],
            'timeTo' => $post['timeTo'],
        ];
        return DatetimeService::createTime($this->userID, (int)$post['weekdayID'], $timeToAdd);
    }

    public static function deleteTime(ParameterDictionary $post)
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }
        return DatetimeService::deleteTime($post['timeID']);
    }

    public function getUserTimeByDayID(ParameterDictionary $post)
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }

        return [
            'time' => DatetimeService::getWeekdayTimeByUserID((int)$post['userID'], (int)$post['weekdayID']),
            'userID' => (int)$post['userID'],
        ];
    }

    public function updateUser()
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }
//        if (!$this->isOwnerOfProfile())
//        {
//            LocalRedirect("/profile/$this->userID/");
//            return;
//        }

        $user = new UserRegisterForm(getPostList());

        $cities = $user->getCityID() == null ? [] : [$user->getCityID()];
        $newSubjectsIDs = [];

        foreach ($user->getNewSubjects() as $subject)
        {
            $newSubjectsIDs[] = $subject['ID'];
        }

        if (!Validator::validateNameField($user->getName())
            || !Validator::validateNameField($user->getLastName())
            || !Validator::validatePhoneNumber($user->getPhoneNumber())
            || !Validator::validateEmail($user->getWorkingEmail())
            || !Validator::validateSubjectsIDs($user->getSubjectsIDs(), false)
            || !Validator::validateSubjectsIDs($newSubjectsIDs, false)
            || !Validator::validateEducationFormatIDs($user->getEdFormatsIDs(), false)
            || !Validator::validateCitiesIDs($cities, false)
        )
        {
            LocalRedirect("/profile/$this->userID/settings/");
            return;
        }
        (new UserService($this->userID))->UpdateUser($user);

        LocalRedirect("/profile/$this->userID/");
    }

    public function deleteSubject(ParameterDictionary $post)
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }
        (new EducationService([$this->userID]))->deleteUserSubject($post['subjectID']);
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

    public function addFeedback(ParameterDictionary $post)
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }
        $feedbackForm = new FeedbackForm($post);
        $title = trim($feedbackForm->getTitle());
        if(strlen($title) > 100 || strlen($title) < 1)
        {
            return (new ErrorService('invalid_len'))->getLastError();
        }
        return (new FeedbackService($this->userID))->add($feedbackForm);
    }

    public function getFeedbacks(ParameterDictionary $post)
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }

        $fbService = new FeedbackService($this->userID);

        $feedbacks =  $fbService->getByPage(
            $post['tutorID'], $post['page'], $post['tutorsPerPage']
        );

        $total = $fbService->getAllFeedbacksCount($post['tutorID']);


        return [
            'feedbacks' => $feedbacks,
            'total' => $total,
        ];
    }
}