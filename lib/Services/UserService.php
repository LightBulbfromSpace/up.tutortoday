<?php

namespace Up\Tutortoday\Services;

use Up\Tutortoday\Model\Tables\FreeTimeTable;
use Up\Tutortoday\Model\Tables\UserSubjectTable;
use Up\Tutortoday\Model\Tables\UserTable;
use Up\Tutortoday\Model\Tables\ContactsTable;
use Up\Tutortoday\Model\Validator;

class UserService
{
    public static function ValidateUser(string $email, string $password)
    {
        if (!Validator::validateEmail($email) || !Validator::validatePassword($password))
        {
            return null;
        }

        $contactsArray = ContactsTable::query()
            ->setSelect(['*'])
            ->where('EMAIL', $email)
            ->fetchCollection();

        $userIDs = [];
        foreach ($contactsArray as $contacts)
        {
            $userIDs[] = $contacts['USER_ID'];
        }
        if ($userIDs === [])
        {
            return null;
        }

        $users = UserTable::query()
            ->setSelect(['*'])
            ->whereIn('ID', $userIDs)
            ->fetchCollection();

        foreach ($users as $user)
        {
            if (password_verify($password, $user['PASSWORD']))
            {
                return $user;
            }
        }
        return null;
    }

    //TODO: refactoring of arguments

    /**
     * @param string $name
     * @param string $surname
     * @param string $middleName
     * @param string $password
     * @param string $email
     * @param string $phone
     * @param string $city
     * @param int $edFormat
     * @param int[] $subject
     * @param string $description
     * @return int|bool
     * @throws \Exception
     */
    public static function CreateUser(
        string $name,  string $surname, string $middleName,
        string $password, string $email, string $phone,
        string $city, int $edFormat, ?array $subjects,
        string $description,
    ) : int|bool
    {
        $resultUser = UserTable::add([
            'NAME' => $name,
            'SURNAME' => $surname,
            'MIDDLE_NAME' => $middleName,
            'PASSWORD' => password_hash($password, PASSWORD_DEFAULT),
            'CITY' => $city,
            'EDUCATION_FORMAT_ID' => $edFormat,
            'ROLE_ID' => 1,
            'DESCRIPTION' => $description,
        ]);
        if (!$resultUser->isSuccess())
        {
            return false;
        }
        $resultContacts = ContactsTable::add([
            'USER_ID' => $resultUser->getId(),
            'EMAIL' => $email,
            'PHONE_NUMBER' => $phone,
        ]);
        if (!$resultContacts->isSuccess())
        {
            return false;
        }

        if ($subjects === null)
        {
            return $resultUser->getId();
        }

        foreach ($subjects as $subject)
        {
            var_dump($subject, $resultUser->getId());
            $resultSubject = UserSubjectTable::add([
                'USER_ID' => $resultUser->getId(),
                'SUBJECT_ID' => $subject,
            ]);
            if (!$resultSubject->isSuccess())
            {
                return false;
            }
        }
        return $resultUser->getId();
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

        $users = UserTable::query()->setSelect(['*'])
            ->where('ROLE_ID', $tutorRoleID)
            ->setOrder(['ID' => 'DESC'])
            ->setOffset($offset)
            ->setLimit(USERS_BY_PAGE);

        return $users?->fetchCollection();
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
        $user = UserTable::query()->setSelect(['*', 'EDUCATION_FORMAT', 'ROLE'])->where('ID', $userID)->fetchObject();
        if ($user === null) {
            return false;
        }
        $contacts = ContactsTable::query()->setSelect(['*'])->where('USER_ID', $userID)->fetchCollection();
        if ($contacts === null) {
            return false;
        }
        $subjects = UserSubjectTable::query()->setSelect(['SUBJECT', 'PRICE'])->where('USER_ID', $userID)->fetchCollection();
        $time = FreeTimeTable::query()->setSelect(['START', 'END', 'WEEKDAY'])->where('USER_ID', $userID)->fetchCollection();
        $timeByWeekdays = [];

        foreach ($time as $item)
        {
            $timeByWeekdays[$item['WEEKDAY']->getName()][] = ['start' => $item['START']->format('H:i'), 'end' => $item['END']->format('H:i')];
        }

        $photo = ImagesService::getProfileImage($userID);
        return [
            'photo' => $photo,
            'mainData' => $user,
            'contacts'=> $contacts,
            'subjects' => $subjects,
            'time' => $timeByWeekdays,
        ];
    }
}