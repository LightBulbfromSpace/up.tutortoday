<?php

namespace Up\Tutortoday\Services;

use Bitrix\Main\SystemException;
use Up\Tutortoday\Model\Tables\RolesTable;
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
        string $city, int $edFormat, int $subject,
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
            'SUBJECT_ID' => $subject === 0 ? null : $subject,
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
        return $resultUser->getId();
    }

    public static function getRoleIDbyName(string $name) : int|bool
    {
        try
        {
            $query = RolesTable::query()->where('NAME', $name);
            $row = $query->fetchObject();
        }
        catch (SystemException $se)
        {
            return false;
        }
        return $row['ID'];
    }

    public static function getUsersByPage(int $page = 1, string $role = 'Tutor')
    {
        $page--;
        $offset = $page * USERS_BY_PAGE;

        $tutorRoleID = self::getRoleIDbyName($role);
        if ($tutorRoleID === false)
        {
            // TODO:Error handling
        }

        try
        {
            $users = UserTable::query()->setSelect(['*'])
                ->where('ROLE_ID', $tutorRoleID)
                ->setOrder(['ID' => 'DESC'])
                ->setOffset($offset)
                ->setLimit(USERS_BY_PAGE)
                ->fetchCollection();
        }
        catch (SystemException $se)
        {
            // TODO:Error handling
            return false;
        }

        return $users;
    }
}