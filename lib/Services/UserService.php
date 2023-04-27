<?php

namespace Up\Tutortoday\Services;

use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\UrlPreview\Parser\Vk;
use Bitrix\Main\UserTable;
use Up\Tutortoday\Model\FormObjects\UserForm;
use Up\Tutortoday\Model\FormObjects\UserRegisterForm;
use Up\Tutortoday\Model\Tables\FreeTimeTable;
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
    private $userID = 0;
    private array $userIDs = [];
    private bool $fetchAllAvailableUsers = false;

    private array $roleIDs = [1];

    public function __construct(int $userID = 0, array $userIDs = [])
    {
        $this->userID = $userID;
        $this->userIDs = $userIDs;
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

    public static function CreateUser(UserRegisterForm $userForm) : bool|string|array
    {
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
            return $resultUser;
        }

        $tutorRoleID = EducationService::getRoleIDbyName('tutor');
        if ($tutorRoleID === null)
        {
            // TODO:Error handling
        }

        $resultUser = $user->Update($user->getID(), [
            'SECOND_NAME' => $userForm->getMiddleName(),
            'WORK_PHONE' => $userForm->getPhoneNumber(),
            'WORK_MAILBOX' => $userForm->getWorkingEmail(),
            'WORK_CITY' => $userForm->getCity(),
            'WORK_POSITION' => $tutorRoleID,
            'WORK_COMPANY' => 'TutorToday',
        ]);

        if ($resultUser == false)
        {
            return $resultUser;
        }

        $resultRole = UserRoleTable::add([
            'USER_ID' => $user->getID(),
            'ROLE_ID' => $userForm->getRoleID(),
        ]);
        if (!$resultRole->isSuccess())
        {
            return $resultRole->getErrorMessages();
        }

        $resultEdFormat = UserEdFormatTable::add([
            'USER_ID' => $user->getID(),
            'EDUCATION_FORMAT_ID' => $userForm->getEdFormatID(),
        ]);
        if (!$resultEdFormat->isSuccess())
        {
            return $resultEdFormat->getErrorMessages();
        }

        foreach ($userForm->getSubjectsIDs() as $subject)
        {
            $resultSubject = UserSubjectTable::add([
                'USER_ID' => $user->getID(),
                'SUBJECT_ID' => $subject,
            ]);
            if (!$resultSubject->isSuccess())
            {
                return $resultSubject->getErrorMessages();
            }
        }

        $resultDescription = UserDescriptionTable::add([
            'USER_ID' => $user->getID(),
            'DESCRIPTION' => $userForm->getDescription(),
        ]);
        if (!$resultDescription->isSuccess())
        {
            return $resultDescription->getErrorMessages();
        }

        return $user->getID();
    }



//    public static function getUsersByPage(int $page = 1, string $role = 'Tutor')
//    {
//        $offset = $page * USERS_BY_PAGE;
//
//        $tutorRoleID = EducationService::getRoleIDbyName($role);
//        if ($tutorRoleID === null)
//        {
//            // TODO:Error handling
//        }
//
//        $users = UserTable::query()
//            ->setSelect(['ID', 'NAME', 'LAST_NAME', 'SECOND_NAME', 'WORK_CITY', 'PERSONAL_PHOTO'])
//            ->where('WORK_POSITION', $tutorRoleID)
//            ->where('WORK_COMPANY', SITE_NAME)
//            ->setOrder(['ID' => 'DESC'])
//            ->setOffset($offset)
//            ->setLimit(USERS_BY_PAGE)
//            ->fetchCollection();
//
//
//        $usersIDs = [];
//
//        foreach ($users as $user)
//        {
//            $usersIDs[] = $user['ID'];
//        }
//
//        $descriptions = UserDescriptionTable::query()
//            ->setSelect(['*'])
//            ->whereIn('USER_ID', $usersIDs)
//            ->fetchCollection();
//
//
//        $result = [];
//        foreach ($users as $i => $user)
//        {
//            $result[$i] = [
//                'fullName' => [
//                    'name' => $user['NAME'],
//                    'lastName' => $user['LAST_NAME'],
//                    'secondName' => $user['SECOND_NAME'],
//                ],
//                'city' => $user['WORK_CITY'],
//                //TODO: change work with photo!!!
//                'photo' => $user['PERSONAL_PHOTO'] != null ? $user['PERSONAL_PHOTO'] : DEFAULT_PHOTO,
//            ];
//            foreach ($descriptions as $description)
//            {
//                if ($user->getID() === $description->getUserId())
//                {
//                    $result[$i]['description'] = $description['DESCRIPTION'];
//                    break;
//                }
//            }
//        }
//        return $result;
//    }

    // Photo
    // Full name
    // Contacts (email, phone, telegram, vk)
    // Education format
    // City
    // Role
    // Subjects
    // Description
    // Feedbacks (in public part)

    public function getUserByID()
    {
        $user = UserTable::query()
            ->setSelect(['*'])
            ->where('ID', $this->userID)
            ->fetchObject();

        if ($user == null)
        {
            return false;
        }

        $role = UserRoleTable::query()
            ->setSelect(['USER_ID', 'ROLE'])
            ->where('USER_ID', $this->userID)
            ->fetchObject();

        if ($role == null)
        {
            return false;
        }

        $edFormat = UserEdFormatTable::query()
            ->setSelect(['USER_ID', 'EDUCATION_FORMAT'])
            ->where('USER_ID', $this->userID)
            ->fetchObject();

        if ($edFormat == null)
        {
            return false;
        }

        $VKs = VkTable::query()
            ->setSelect(['USER_ID', 'VK_PROFILE'])
            ->where('USER_ID', $this->userID)
            ->fetchCollection();

        $telegrams = TelegramTable::query()
            ->setSelect(['USER_ID', 'TELEGRAM_USERNAME'])
            ->where('USER_ID', $this->userID)
            ->fetchCollection();
        $subjects = UserSubjectTable::query()
            ->setSelect(['USER_ID', 'SUBJECT', 'PRICE'])
            ->where('USER_ID', $this->userID)
            ->fetchCollection();

        $description = UserDescriptionTable::query()
            ->setSelect(['USER_ID', 'DESCRIPTION'])
            ->where('USER_ID', $this->userID)
            ->fetchObject();

        return [
            'photo' => $user['PERSONAL_PHOTO'] != null ? $user['PERSONAL_PHOTO'] : DEFAULT_PHOTO,
            'mainData' => $user,
            'role' => $role->getRole(),
            'edFormat' => $edFormat->getEducationFormat(),
            'city' => $user['WORK_CITY'],
            'description' => $description['DESCRIPTION'],
            'contacts'=> [
                'phone' => $user['WORK_PHONE'],
                'email' => $user['WORK_MAILBOX'],
                'vk' => $VKs,
                'telegram' => $telegrams,
            ],
            'subjects' => $subjects,
        ];
    }

    public function getUsersByPage(int $offset = 0, int $limit = 50)
    {
        if ($offset < 0)
        {
            return false;
        }

        $query = UserTable::query()
            ->setSelect(['ID', 'NAME', 'LAST_NAME', 'SECOND_NAME', 'WORK_CITY', 'PERSONAL_PHOTO'])
            ->whereIn('WORK_POSITION', $this->roleIDs)
            ->where('WORK_COMPANY', SITE_NAME)
            ->setOrder(['ID' => 'DESC'])
            ->setOffset($offset)
            ->setLimit($limit);

        if (!$this->fetchAllAvailableUsers)
        {
            $query->whereIn('ID', $this->userIDs);
        }

        $users = $query->fetchCollection();

        if ($users == null)
        {
            return false;
        }

        $fetchedUserIDs = [];
        foreach ($users as $user)
        {
            $fetchedUserIDs[] = $user['ID'];
        }

        $descriptions = UserDescriptionTable::query()
            ->setSelect(['USER_ID', 'DESCRIPTION'])
            ->whereIn('USER_ID', $fetchedUserIDs)
            ->fetchCollection();

        $subjects = UserSubjectTable::query()
            ->setSelect(['*', 'SUBJECT'])
            ->whereIn('USER_ID', $fetchedUserIDs)
            ->fetchCollection();

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
                'city' => $user['WORK_CITY'],
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
                    $result[$i]['subjects'][] = $subject['SUBJECT'];
                }
            }

        }

        return $result;
    }

    public function UpdateUser(UserRegisterForm $userForm)
    {
        $user = new \CUser();
        $userResult = $user->update($this->userID, [
                'NAME' => $userForm->getName(),
                'LAST_NAME' => $userForm->getLastName(),
                'SECOND_NAME' => $userForm->getMiddleName(),
                'WORK_CITY' => $userForm->getCity(),
                'WORK_PHONE' => $userForm->getPhoneNumber(),
                'WORK_MAILBOX' => $userForm->getWorkingEmail(),
        ]);
        if ($userResult !== true)
        {
            return $userResult;
        }

        $descriptionResult = UserDescriptionTable::update($this->userID, [
            'DESCRIPTION' => $userForm->getDescription()
        ]);
        if (!$descriptionResult->isSuccess())
        {
            return 'description update error';
        }

        $edFormatResult = UserEdFormatTable::update([
            'USER_ID' => $this->userID,
            'EDUCATION_FORMAT_ID' =>$userForm->getEdFormatID()
        ], [
            'EDUCATION_FORMAT_ID' => $userForm->getEdFormatID(),
        ]);
        if (!$edFormatResult->isSuccess())
        {
            return 'description update error';
        }

        foreach ($userForm->getExistingSubjectsPrices() as $subject)
        {
            if ($subject['price'] < 0) {
                continue;
            }
            $subjAddResult = UserSubjectTable::update([
                'USER_ID' => $this->userID,
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
            ->where('USER_ID', $this->userID)
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
        foreach ($subjectsToAdd as $subj)
        {
            $subjAddResult = UserSubjectTable::add([
                'USER_ID' => $this->userID,
                'SUBJECT_ID' => $subj['ID'],
                'PRICE' => $subj['price'],
            ]);
            if (!$subjAddResult->isSuccess())
            {
                return $subjAddResult->getErrorMessages();
            }
        }
        //role update

        return true;
    }
}