<?php

namespace Up\Tutortoday\Services;

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

    public function __construct(int $userID)
    {
        $this->userID = $userID;
    }

//    public static function ValidateUser(string $email, string $password)
//    {
//        if (!Validator::validateEmail($email) || !Validator::validatePassword($password))
//        {
//            return null;
//        }
//
//        $contactsArray = EmailTable::query()
//            ->setSelect(['*'])
//            ->where('EMAIL', $email)
//            ->fetchCollection();
//
//        $userIDs = [];
//        foreach ($contactsArray as $contacts)
//        {
//            $userIDs[] = $contacts['USER_ID'];
//        }
//        if ($userIDs === [])
//        {
//            return null;
//        }
//
//        $users = UserTable::query()
//            ->setSelect(['*'])
//            ->whereIn('ID', $userIDs)
//            ->fetchCollection();
//
//        foreach ($users as $user)
//        {
//            if (password_verify($password, $user['PASSWORD']))
//            {
//                return $user;
//            }
//        }
//        return null;
//    }


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



    public static function getUsersByPage(int $page = 1, string $role = 'Tutor')
    {
        $page--;
        $offset = $page * USERS_BY_PAGE;

        $tutorRoleID = EducationService::getRoleIDbyName($role);
        if ($tutorRoleID === null)
        {
            // TODO:Error handling
        }

        $users = UserTable::query()
//            ->registerRuntimeField('d', [
//                'data_type' => UserDescriptionTable::class,
//                'reference' => [
//                    '=this.ID' => 'ref.USER_ID',
//                ],
//                'join_type' => 'right'
//            ])
            ->setSelect(['ID', 'NAME', 'LAST_NAME', 'SECOND_NAME', 'WORK_CITY', 'PERSONAL_PHOTO'])
            ->where('WORK_POSITION', $tutorRoleID)
            ->where('WORK_COMPANY', SITE_NAME)
            ->setOrder(['ID' => 'DESC'])
            ->setOffset($offset)
            ->setLimit(USERS_BY_PAGE)
            ->fetchCollection();


        $usersIDs = [];

        foreach ($users as $user)
        {
            $usersIDs[] = $user['ID'];
        }

        $descriptions = UserDescriptionTable::query()
            ->setSelect(['*'])
            ->whereIn('USER_ID', $usersIDs)
            ->fetchCollection();


        $result = [];
        foreach ($users as $i => $user)
        {
            $result[$i] = [
                'mainData' => $user,
                //TODO: change work with photo!!!
                'photo' => $user['PERSONAL_PHOTO'] != null ? $user['PERSONAL_PHOTO'] : DEFAULT_PHOTO,
            ];
            foreach ($descriptions as $description)
            {
                if ($user->getID() === $description->getUserId())
                {
                    $result[$i]['description'] = $description;
                    break;
                }
            }
        }
        return $result;
    }

    // Photo
    // Full name
    // Contacts (email, phone, telegram, vk)
    // Education format
    // City
    // Role
    // Subjects
    // Description
    // Feedbacks (in public part)
    public static function getUserByID($userID)
    {
        $user = \CUser::GetByID($userID)->Fetch();
        if ($user == null) {
            return false;
        }
        $role = UserRoleTable::query()->setSelect(['ROLE'])->where('USER_ID', $userID)->fetchObject();
        if ($role == null) {
            return false;
        }

        $edFormat = UserEdFormatTable::query()->setSelect(['EDUCATION_FORMAT'])->where('USER_ID', $userID)->fetchObject();
        if ($edFormat == null) {
            return false;
        }

        $VKs = VkTable::query()->setSelect(['VK_PROFILE'])->where('USER_ID', $userID)->fetchCollection();
        $telegrams = TelegramTable::query()->setSelect(['TELEGRAM_USERNAME'])->where('USER_ID', $userID)->fetchCollection();
        $subjects = UserSubjectTable::query()->setSelect(['SUBJECT', 'PRICE'])->where('USER_ID', $userID)->fetchCollection();
//        $time = FreeTimeTable::query()->setSelect(['START', 'END', 'WEEKDAY', 'WEEKDAY_ID'])->where('USER_ID', $userID)->fetchCollection();
//        $timeByWeekdays = [];
//
//        foreach ($time as $item)
//        {
//            $timeByWeekdays[$item['WEEKDAY']->getName()][] = ['start' => $item['START']->format('H:i'), 'end' => $item['END']->format('H:i')];
//        }

        $description = UserDescriptionTable::query()->setSelect(['DESCRIPTION'])->where('USER_ID', $userID)->fetchObject();
        $photo = ImagesService::getProfileImage($userID);
        return [
            'photo' => $photo,
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
//            'time' => $timeByWeekdays,
        ];
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