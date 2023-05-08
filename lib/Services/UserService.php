<?php

namespace Up\Tutortoday\Services;

use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\UrlPreview\Parser\Vk;
use Bitrix\Main\UserTable;
use CUser;
use Up\Tutortoday\Model\FormObjects\UserForm;
use Up\Tutortoday\Model\FormObjects\UserRegisterForm;
use Up\Tutortoday\Model\Tables\CitiesTable;
use Up\Tutortoday\Model\Tables\FeedbacksTable;
use Up\Tutortoday\Model\Tables\FreeTimeTable;
use Up\Tutortoday\Model\Tables\ProfileImagesTable;
use Up\Tutortoday\Model\Tables\RolesTable;
use Up\Tutortoday\Model\Tables\SubjectTable;
use Up\Tutortoday\Model\Tables\TelegramTable;
use Up\Tutortoday\Model\Tables\UserDescriptionTable;
use Up\Tutortoday\Model\Tables\UserEdFormatTable;
use Up\Tutortoday\Model\Tables\UserRoleTable;
use Up\Tutortoday\Model\Tables\UserSubjectTable;
use Up\Tutortoday\Model\Tables\VkTable;
use Up\Tutortoday\Model\Validator;

class UserService
{
    private $observedUserID;
    private array $userIDs = [];
    private int $numOfFetchedUsers = 0;
    private bool $fetchAllAvailableUsers = false;
    private array $roleIDs = [1];
    private bool $onlyUnblocked = false;
    public function __construct(int $userID = 0, array $userIDs = [])
    {
        $this->observedUserID = $userID;
        $this->userIDs = $userIDs;
    }

    public function getNumOfFetchedUsers(): int
    {
        return $this->numOfFetchedUsers;
    }

    public function getOnlyUnblockedUsers(bool $isUnblockedUsers)
    {
        $this->onlyUnblocked = $isUnblockedUsers;
    }

    public function UpdatePassword(string $oldPassword, string $newPassword, string $passwordConfirm)
    {
        global $USER;
        return $USER->ChangePassword(
            $USER->GetLogin(), '',
            $newPassword, $passwordConfirm,
            false, '',
            0, true,
            '', $oldPassword
        );
    }

    public function CountAllAvailableUsers(): int
    {
        return UserTable::query()
            ->setSelect(['ID'])
            ->whereIn('WORK_POSITION', $this->roleIDs)
            ->where('WORK_COMPANY', SITE_NAME)
            ->fetchCollection()
            ->count();
    }


    public function setFetchAllAvailableUsers(bool $fetchAllAvailableUsers): void
    {
        $this->fetchAllAvailableUsers = $fetchAllAvailableUsers;
    }

    /**
     * @param string[] $rolesNames
     */
    public function setRoles(array $rolesNames): void
    {
        $roleIDs = RolesTable::query()
            ->setSelect(['ID'])
            ->whereIn('NAME', $rolesNames)
            ->fetchCollection();
        foreach ($roleIDs as $roleID)
        {
            $this->roleIDs[] = $roleID['ID'];
        }
    }


    /**
     * @param int[] $rolesIDs
     */
    public function setRolesByIDs(array $rolesIDs): void
    {
            $this->roleIDs = $rolesIDs;
    }

    public static function CreateUser(UserRegisterForm $userForm) : bool|string|array
    {
        global $DB;
        $DB->StartTransaction();
        $user = new \CUser();
        $resultUser = $user->Register(
            $userForm->getLogin(),
            $userForm->getName(),
            $userForm->getLastName(),
            $userForm->getPassword(),
            $userForm->getConfirmPassword(),
            $userForm->getEmail(),
        );

        if ($resultUser['TYPE'] !== 'OK')
        {
            $DB->rollback();
            return $resultUser;
        }

        $resultUser = $user->Update($user->getID(), [
            'SECOND_NAME' => $userForm->getMiddleName(),
            'WORK_PHONE' => $userForm->getPhoneNumber(),
            'WORK_MAILBOX' => $userForm->getWorkingEmail(),
            'WORK_CITY' => $userForm->getCityID(),
            'WORK_POSITION' => $userForm->getRoleID(),
            'WORK_COMPANY' => 'TutorToday',
        ]);

        if (!$resultUser)
        {
            $DB->rollback();
            return $resultUser;
        }

        $resultRole = UserRoleTable::add([
            'USER_ID' => $user->getID(),
            'ROLE_ID' => $userForm->getRoleID(),
        ]);
        if (!$resultRole->isSuccess())
        {
            $DB->rollback();
            return $resultRole->getErrorMessages();
        }

        foreach ($userForm->getEdFormatsIDs() as $edFormatID) {
            $resultEdFormat = UserEdFormatTable::add([
                'USER_ID' => $user->getID(),
                'EDUCATION_FORMAT_ID' => $edFormatID,
            ]);
            if (!$resultEdFormat->isSuccess()) {
                return $resultEdFormat->getErrorMessages();
            }
        }

        foreach ($userForm->getSubjectsIDs() as $subject)
        {
            $resultSubject = UserSubjectTable::add([
                'USER_ID' => $user->getID(),
                'SUBJECT_ID' => $subject,
                'PRICE' => 0,
            ]);
            if (!$resultSubject->isSuccess())
            {
                $DB->rollback();
                return $resultSubject->getErrorMessages();
            }
        }

        $resultDescription = UserDescriptionTable::add([
            'USER_ID' => $user->getID(),
            'DESCRIPTION' => $userForm->getDescription(),
        ]);
        if (!$resultDescription->isSuccess())
        {
            $DB->rollback();
            return $resultDescription->getErrorMessages();
        }

        $DB->Commit();
        return $user->getID();
    }

    public function getUserByID(int $observerID = 0)
    {
        $user = UserTable::query()
            ->setSelect(['*'])
            ->where('ID', $this->observedUserID)
            ->fetchObject();

        if ($user == null)
        {
            return false;
        }

        $role = UserRoleTable::query()
            ->setSelect(['USER_ID', 'ROLE'])
            ->where('USER_ID', $this->observedUserID)
            ->fetchObject();

        if ($role == null)
        {
            return false;
        }

        $edFormats = UserEdFormatTable::query()
            ->setSelect(['USER_ID', 'EDUCATION_FORMAT'])
            ->where('USER_ID', $this->observedUserID)
            ->fetchCollection();

        $VKs = VkTable::query()
            ->setSelect(['USER_ID', 'VK_PROFILE'])
            ->where('USER_ID', $this->observedUserID)
            ->fetchCollection();

        $telegrams = TelegramTable::query()
            ->setSelect(['USER_ID', 'TELEGRAM_USERNAME'])
            ->where('USER_ID', $this->observedUserID)
            ->fetchCollection();
        $subjects = UserSubjectTable::query()
            ->setSelect(['USER_ID', 'SUBJECT', 'PRICE'])
            ->where('USER_ID', $this->observedUserID)
            ->fetchCollection();

        $description = UserDescriptionTable::query()
            ->setSelect(['USER_ID', 'DESCRIPTION'])
            ->where('USER_ID', $this->observedUserID)
            ->fetchObject();

        $city = LocationService::getCityNameByID((int)$user['WORK_CITY']);

        $photo = (new ImagesService($this->observedUserID))->getProfileImage();
        $feedbacks = [];

        $observer = UserRoleTable::query()
            ->setSelect(['USER_ID', 'ROLE'])
            ->where('USER_ID', $observerID)
            ->fetchObject();

        if ($observer['ROLE']['NAME'] !== 'tutor' && $observerID !== 0)
        {
            $feedbacks = (new FeedbackService($observerID))->getByPage($this->observedUserID, 0);
        }


        return [
            'photo' => $photo != null ? $photo['LINK'] : DEFAULT_PHOTO,
            'mainData' => $user,
            'role' => $role->getRole(),
            'edFormats' => $edFormats,
            'city' => $city,
            'description' => $description['DESCRIPTION'],
            'contacts'=> [
                'phone' => $user['WORK_PHONE'],
                'email' => $user['WORK_MAILBOX'],
                'vk' => $VKs,
                'telegram' => $telegrams,
            ],
            'subjects' => $subjects,
            'feedbacks' => $feedbacks ?? [],
            'observer' => [
                'ID' => $observer['USER_ID'],
                'role' => $observer['ROLE'],
            ]
        ];
    }

    public function getUsersByPage(int $offset = 0, int $limit = 50, bool $short = false)
    {
        if ($offset < 0)
        {
            return false;
        }
        if ($this->observedUserID === 0 && $this->userIDs === [] && !$this->fetchAllAvailableUsers)
        {
            $this->numOfFetchedUsers = 0;
            return [];
        }

        $queryForCount = UserTable::query()
            ->setSelect(['ID'])
            ->whereIn('WORK_POSITION', $this->roleIDs)
            ->where('WORK_COMPANY', SITE_NAME);

        $query = UserTable::query()
            ->setSelect(['ID', 'NAME', 'LAST_NAME', 'SECOND_NAME', 'WORK_CITY', 'WORK_POSITION', 'BLOCKED'])
            ->whereIn('WORK_POSITION', $this->roleIDs)
            ->where('WORK_COMPANY', SITE_NAME)
            ->setOrder(['ID' => 'DESC'])
            ->setOffset($offset)
            ->setLimit($limit);

        if (!$this->fetchAllAvailableUsers)
        {
            $queryForCount->whereIn('ID', $this->userIDs);
            $query->whereIn('ID', $this->userIDs);
        }

        if ($this->onlyUnblocked)
        {
            $queryForCount->where('BLOCKED', 'N');
            $query->where('BLOCKED', 'N');
        }

        $users = $query->fetchCollection();

        $this->numOfFetchedUsers = $queryForCount->fetchCollection()->count();

        $fetchedUserIDs = [];
        foreach ($users as $user)
        {
            $fetchedUserIDs[] = $user['ID'];
        }

        $result = [];

        $roles = RolesTable::query()
            ->setSelect(['*'])
            ->whereIn('ID', $this->roleIDs)
            ->fetchCollection();

        foreach ($users as $i => $user)
        {
            $result[$i] = [
                'ID' => $user['ID'],
                'fullName' => [
                    'name' => $user['NAME'],
                    'lastName' => $user['LAST_NAME'],
                    'secondName' => $user['SECOND_NAME'],
                ],
                'blocked' => $user['BLOCKED'],
            ];
            foreach ($roles as $role)
            {
                if ((int)$user['WORK_POSITION'] === (int)$role['ID'])
                {
                    $result[$i]['ROLE'] = [
                        'ID' => $role['ID'],
                        'NAME' => $role['NAME'],
                    ];
                    break;
                }
            }
        }

        if ($short) {
            return $result;
        }

        $descriptions = UserDescriptionTable::query()
            ->setSelect(['USER_ID', 'DESCRIPTION'])
            ->whereIn('USER_ID', $fetchedUserIDs)
            ->fetchCollection();

        $subjects = UserSubjectTable::query()
            ->setSelect(['*', 'SUBJECT'])
            ->whereIn('USER_ID', $fetchedUserIDs)
            ->fetchCollection();

        $edFormats = UserEdFormatTable::query()
            ->setSelect(['*', 'EDUCATION_FORMAT'])
            ->whereIn('USER_ID', $fetchedUserIDs)
            ->fetchCollection();

        $cities = LocationService::getAllCities();

        $photos = (new ImagesService())->getProfileImages($this->userIDs);


        //TODO: change this
        $result = [];

        foreach ($users as $i => $user)
        {
            $result[$i] = [
                'ID' => $user['ID'],
                'photo' => $user['PERSONAL_PHOTO'] != null ? $user['PERSONAL_PHOTO'] : DEFAULT_PHOTO,
                'fullName' => [
                    'name' => $user['NAME'],
                    'lastName' => $user['LAST_NAME'],
                    'secondName' => $user['SECOND_NAME'],
                ],
            ];
            foreach ($descriptions as $description)
            {
                if ($user['ID'] === $description['USER_ID'])
                {
                    $result[$i]['description'] = $description['DESCRIPTION'];
                    break;
                }
            }
            foreach ($subjects as $subject)
            {
                if ($user['ID'] === $subject['USER_ID'])
                {
                    $result[$i]['subjects'][] = [
                        'NAME' => $subject['SUBJECT']['NAME'],
                        'ID' => $subject['SUBJECT']['ID'],
                        'PRICE' => $subject['PRICE'],
                    ];
                }
            }
            foreach ($edFormats as $edFormat)
            {
                if ($user['ID'] === $edFormat['USER_ID'])
                {
                    $result[$i]['edFormat'][] = $edFormat['EDUCATION_FORMAT'];
                }
            }
            foreach ($cities as $city)
            {
                if((int)$user['WORK_CITY'] === (int)$city['ID'])
                {
                    $result[$i]['city'] = $city;
                    break;
                }
            }
            foreach ($photos as $photo)
            {
                if ($user['ID'] === $photo['USER_ID'])
                {
                    $result[$i]['photo'] = $photo['LINK'];
                }
            }
        }

        return $result;
    }

    public function UpdateUser(UserRegisterForm $userForm)
    {
        $user = new \CUser();
        $userResult = $user->update($this->observedUserID, [
                'NAME' => $userForm->getName(),
                'LAST_NAME' => $userForm->getLastName(),
                'SECOND_NAME' => $userForm->getMiddleName(),
                'WORK_CITY' => $userForm->getCityID(),
                'WORK_PHONE' => $userForm->getPhoneNumber(),
                'WORK_MAILBOX' => $userForm->getWorkingEmail(),
        ]);
        if ($userResult !== true)
        {
            return $userResult;
        }

        $descriptionResult = UserDescriptionTable::update($this->observedUserID, [
            'DESCRIPTION' => $userForm->getDescription()
        ]);
        if (!$descriptionResult->isSuccess())
        {
            return 'description update error';
        }

        $existingEdFormats = UserEdFormatTable::query()
            ->setSelect(['*'])
            ->where('USER_ID', $this->observedUserID)
            ->fetchCollection();

        foreach ($existingEdFormats as $format)
        {
            $edFormatResult = UserEdFormatTable::delete([
                'USER_ID' => $format['USER_ID'],
                'EDUCATION_FORMAT_ID' => $format['EDUCATION_FORMAT_ID'],
            ]);
            if (!$edFormatResult->isSuccess())
            {
                return 'education format update error';
            }
        }

        foreach ($userForm->getEdFormatsIDs() as $edFormatID)
        {
            $edFormatResult = UserEdFormatTable::add([
                'USER_ID' => $this->observedUserID,
                'EDUCATION_FORMAT_ID' => $edFormatID
            ]);
            if (!$edFormatResult->isSuccess())
            {
                return 'education format error';
            }
        }

        foreach ($userForm->getExistingSubjectsPrices() as $subject)
        {
            if ($subject['price'] < 0) {
                continue;
            }
            $subjAddResult = UserSubjectTable::update([
                'USER_ID' => $this->observedUserID,
                'SUBJECT_ID' =>$subject['ID'],
                ], [
                    'PRICE' => $subject['price'],
            ]);
            if (!$subjAddResult->isSuccess())
            {
                return 'subject\'s price update error';
            }
        }

        $subjectsToAdd = [];

        $existingSubjectsIDs = UserSubjectTable::query()
            ->setSelect(['SUBJECT_ID'])
            ->where('USER_ID', $this->observedUserID)
            ->fetchCollection();

        if ($existingSubjectsIDs != null)
        {
            foreach ($userForm->getNewSubjects() as $newSubj)
            {
                $inArray = false;
                foreach ($existingSubjectsIDs as $exSubjID)
                {
                    if ($newSubj['ID'] === $exSubjID)
                    {
                        $inArray = true;
                        break;
                    }
                }
                if (!$inArray)
                {
                    $subjectsToAdd[] = $newSubj;
                }
            }
        }

        $addedSubjectsIDs = [];
        foreach ($subjectsToAdd as $subj)
        {
            if (in_array($subj['ID'], $addedSubjectsIDs))
            {
                continue;
            }
            $subjAddResult = UserSubjectTable::add([
                'USER_ID' => $this->observedUserID,
                'SUBJECT_ID' => $subj['ID'],
                'PRICE' => $subj['price'],
            ]);
            $addedSubjectsIDs[] = $subj['ID'];
            if (!$subjAddResult->isSuccess())
            {
                return $subjAddResult->getErrorMessages();
            }
        }

        return true;
    }

    public function deleteUser()
    {
        \CUser::Delete($this->observedUserID);
        $roles = UserRoleTable::query()->setSelect(['*'])->where('USER_ID', $this->observedUserID)->fetchCollection();
        foreach ($roles as $role)
        {
            UserRoleTable::delete([
                'USER_ID' => $role['USER_ID'],
                'ROLE_ID' => $role['ROLE_ID'],
            ]);
        }
        $subjects = UserSubjectTable::query()->setSelect(['*'])->where('USER_ID', $this->observedUserID)->fetchCollection();
        foreach ($subjects as $subject)
        {
            UserSubjectTable::delete([
                'USER_ID' => $subject['USER_ID'],
                'SUBJECT_ID' => $subject['SUBJECT_ID'],
            ]);
        }
        $edFormats = UserEdFormatTable::query()->setSelect(['*'])->where('USER_ID', $this->observedUserID)->fetchCollection();
        foreach ($edFormats as $edFormat)
        {
            UserEdFormatTable::delete([
                'USER_ID' => $edFormat['USER_ID'],
                'EDUCATION_FORMAT_ID' => $edFormat['EDUCATION_FORMAT_ID'],
            ]);
        }
        $feedbacks = FeedbacksTable::query()->setSelect(['*'])->where('TUTOR_ID', $this->observedUserID)->fetchCollection();
        foreach ($feedbacks as $feedback)
        {
            FeedbacksTable::delete([
                'ID' => $feedback['ID'],
            ]);
        }
        $freeTime = FreeTimeTable::query()->setSelect(['*'])->where('USER_ID', $this->observedUserID)->fetchCollection();
        foreach ($freeTime as $hour)
        {
            FreeTimeTable::delete([
                'ID' => $hour['ID'],
            ]);
        }

        $images = ProfileImagesTable::query()
            ->setSelect(['ID'])
            ->where('USER_ID', $this->observedUserID)
            ->fetchCollection();

        foreach ($images as $image)
        {
            ProfileImagesTable::delete([
                'ID' => $image['ID'],
            ]);
        }

        $VKs = VkTable::query()
            ->setSelect(['*'])
            ->where('USER_ID', $this->observedUserID)
            ->fetchCollection();
        foreach ($VKs as $VK)
        {
            VkTable::delete([
                'ID' => $VK['ID'],
            ]);
        }
        $telegramUsernames = TelegramTable::query()
            ->setSelect(['*'])
            ->where('USER_ID', $this->observedUserID)
            ->fetchCollection();
        foreach ($telegramUsernames as $telegramUsername)
        {
            TelegramTable::delete([
                'ID' => $telegramUsername['ID'],
            ]);
        }
    }

    public function saveProfilePhoto()
    {
        $service = (new ImagesService($this->observedUserID));
        $file = $service->getLastTmpFile();
        if ($file === null)
        {
            (new ErrorService('file_not_found'))->getLastError();
        }
        $service->clearTrash($service->getAvatarDir());

        $name = preg_replace('#.+[\\\/]#', '', $file);
        $newPlace = $service->saveProfileImage($name);

        $service->clearTrash($service->getTmpDir());
        if (!$service->getErrors()->isNoErrors())
        {
            return $service->getErrors()->getLastError();
        }
        return ['TYPE' => 'OK', 'MESSAGE' => $service::cutPathToProjectRoot($newPlace)];
    }

    public function getPreferences()
    {
        $preferences = [];

        $edFormats = UserEdFormatTable::query()
            ->setSelect(['EDUCATION_FORMAT_ID'])
            ->where('USER_ID', $this->observedUserID)
            ->fetchCollection();
        foreach ($edFormats as $edFormat)
        {
            $preferences['edFormats'][] = $edFormat['EDUCATION_FORMAT_ID'];
        }
        $subjects = UserSubjectTable::query()
            ->setSelect(['SUBJECT_ID'])
            ->where('USER_ID', $this->observedUserID)
            ->fetchCollection();
        foreach ($subjects as $subject)
        {
            $preferences['subjects'][] = $subject['SUBJECT_ID'];
        }

        $city = UserTable::query()
            ->setSelect(['WORK_CITY'])
            ->where('ID', $this->observedUserID)
            ->fetchObject();

        if ($city['WORK_CITY'] !== '')
        {
            $preferences['cities'][] = $city['WORK_CITY'];
        }
        return $preferences;
    }

    public function getUserRoleByID()
    {
        $result = UserTable::query()
            ->setSelect(['WORK_POSITION'])
            ->where('ID', $this->observedUserID)
            ->fetchObject();

        $roleID = $result['WORK_POSITION'];

        $result = RolesTable::query()
            ->setSelect(['NAME'])
            ->where('ID', $roleID)
            ->fetchObject();

        return [
            'ID' => $roleID,
            'NAME' => $result['NAME'],
        ];
    }

    public function setBlockStatus(int $userID, string $blocked)
    {
        $user = new CUser();
        return $user->Update($userID, [
            'BLOCKED' => $blocked,
        ]);
    }

    public static function getUserIDbyLogin($login) : int
    {
        $result = UserTable::query()
            ->setSelect(['ID'])
            ->where('LOGIN', $login)
            ->fetchObject();
        return (int)$result['ID'];
    }

    public function isBlocked()
    {
        $result = UserTable::query()
            ->setSelect(['BLOCKED'])
            ->where('ID', $this->observedUserID)
            ->fetchObject();

        return $result['BLOCKED'];
    }
}