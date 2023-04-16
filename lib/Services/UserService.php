<?php

namespace Up\Tutortoday\Services;

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
        $IDsForWhere = $userIDs[0];
        if (count($userIDs) > 1)
        {
            $IDsForWhere = implode(' OR ', $userIDs);
        }

        $users = UserTable::query()
            ->setSelect(['*'])
            ->where('ID', $IDsForWhere)
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

    public static function CreateUser(
        string $name,  string $surname, string $middleName,
        string $password, string $email, string $phone,
        string $city, int $edFormat, int $subject
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
            'SUBJECT_ID' => $subject === 0 ? null : $subject,
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
        return $resultUser->getId();
    }
}