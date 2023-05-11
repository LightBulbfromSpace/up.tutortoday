<?php

namespace Up\Tutortoday\Controller;

use Bitrix\Main\Type\ParameterDictionary;
use Up\Tutortoday\Model\FormObjects\FeedbackForm;
use Up\Tutortoday\Model\FormObjects\UserForm;
use Up\Tutortoday\Model\FormObjects\WeekdayTimeForm;
use Up\Tutortoday\Model\Validator;
use Up\Tutortoday\Providers\DatetimeProvider;
use Up\Tutortoday\Providers\FeedbackProvider;
use Up\Tutortoday\Providers\SubjectsProvider;
use Up\Tutortoday\Services\EdFormatsService;
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

        return (new UserService(new UserForm($this->userID)))
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

        (new UserService(new UserForm($this->userID)))->deleteEntity();

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

        try
        {
            $start = date_timestamp_get(new \DateTime($post['timeFrom']));
            $end = date_timestamp_get(new \DateTime($post['timeTo']));
        }
        catch (\Exception $e)
        {
            return null;
        }

        if (($end - $start) < 0)
        {
            return null;
        }

        $timeForm = new WeekdayTimeForm(
            null, (int)$post['weekdayID'], $post['timeFrom'], $post['timeTo']
        );

        return (new DatetimeService($this->userID, $timeForm))->addNewEntity();
    }

    public static function deleteTime(ParameterDictionary $post) : array
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }

        $timeFrom = new WeekdayTimeForm((int)$post['timeID']);

        $result = (new DatetimeService(null, $timeFrom))->deleteEntity();
        if ($result)
        {
            return (new ErrorService('ok'))->getLastError();
        }

        return (new ErrorService('del_err'))->getLastError();
    }

    public function getUserTimeByDayID(ParameterDictionary $post) : array
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }

        return [
            'time' => (new DatetimeProvider((int)$post['userID'], (int)$post['weekdayID']))->getWeekdayTimeByUserID(),
            'userID' => (int)$post['userID'],
        ];
    }

    public function updateUser()
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }

//        $this->login = $post['login'] ?? '';
//        $this->name = trim($post['name']);
//        $this->lastName = trim($post['lastName']);
//        $this->middleName = trim($post['middleName']) ?? '';
//        $this->oldPassword = trim($post['oldPassword']) ?? '';
//        $this->password = trim($post['password']) ?? '';
//        $this->confirmPassword = trim($post['confirmPassword']) ?? '';
//        $this->email = trim($post['email']) ?? '';
//        $this->workingEmail = trim($post['workingEmail']);
//        $this->phoneNumber = trim($post['phoneNumber']);
//        $this->edFormatsIDs = $post['edFormats'] ?? [];
//        $this->description = $post['description'];
//        $this->cityID = !is_numeric($post['city']) ? null : (int)$post['city'];
//        $this->roleID = !is_numeric($post['role']) ? null : (int)$post['role'];
//        $this->subjectsIDs = $post['subjects'] ?? [];
//        foreach ($post['subjectsPrices'] as $ID => $subjectPrice)
//        {
//            $this->existingSubjectsPrices[] = [
//                'ID' => $ID,
//                'price' => $subjectPrice,
//            ];
//        }
//        $this->newSubjects = [];
//        if (isset($post['newSubjectsID']) && $post['newSubjectsID'] != null)
//        {
//            foreach ($post['newSubjectsID'] as $i => $subjectID)
//            {
//                $this->newSubjects[] = [
//                    'ID' => (int)$subjectID,
//                    'price' => $post['newSubjectsPrices'][$i]
//                ];
//            }
//        }

        $post = getPostList();

        $cityID = !is_numeric($post['city']) ? null : (int)$post['city'];

        $user = new UserForm(
            $this->userID,
            $post['name'], $post['lastName'], $post['middleName'],
            null, null, null, null, null,
            null, $post['workingEmail'], $post['phoneNumber'],
            $post['description'],
            $cityID,
            $post['edFormats'],
            $post['subjects'], $post['subjectsPrices'],
            $post['newSubjectsID'], $post['newSubjectsPrices']
        );

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
        (new UserService($user))->editEntity();

        LocalRedirect("/profile/$this->userID/");
    }

    public function deleteSubject(ParameterDictionary $post)
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }
        (new UserService(new UserForm($this->userID)))->deleteUserSubject((int)$post['subjectID']);
    }

    public static function getAllSubjects() : array
    {
        $subjectsRaw = SubjectsProvider::getAllSubjects();
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
        return (new UserService(new UserForm($this->userID)))->saveProfilePhoto();
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
        return (new FeedbackService($this->userID, $feedbackForm))->addNewEntity();
    }

    public function getFeedbacks(ParameterDictionary $post)
    {
        if (!check_bitrix_sessid())
        {
            return (new ErrorService('invalid_csrf'))->getLastError();
        }

        $fbService = new FeedbackProvider($this->userID);

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