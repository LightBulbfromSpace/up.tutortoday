<?php

namespace Up\Tutortoday\Controller;

use Bitrix\Main\Engine\Controller;
use Up\Tutortoday\Model\FormObjects\UserForm;
use Up\Tutortoday\Model\Validator;
use Up\Tutortoday\Providers\UserProvider;
use Up\Tutortoday\Services\UserService;
use Bitrix\Main\PhoneNumber\Parser;

class AuthController extends Controller
{
    public static function LoginAction()
    {
        if(!check_bitrix_sessid())
        {
            return;
        }
        $post = getPostList();
        if ($post['login'] == null || $post['password'] == null)
        {
            LocalRedirect("/login/?err=empty_field");
        }

        global $USER;

        if (is_object($USER))
        {
            $USER->Logout();
        }

        global $USER;
        $USER = new \CUser();

        $result = $USER->Login($post['login'], $post['password']);

        if ($result !== true)
        {
            $user = new UserForm(UserProvider::getUserIDbyLogin($post['login']));
            if ((new UserService($user))->isBlocked())
            {
                LocalRedirect("/login/?err=blocked");
                return;
            }
            LocalRedirect("/login/?err=auth");
            return;
        }

        $userRole = (new UserProvider($USER->GetID()))->getUserRoleByID();

        if ($userRole['NAME'] === 'administrator')
        {
            LocalRedirect('/admin/');
            return;
        }

        LocalRedirect("/profile/{$USER->getID()}/");
    }

    public static function RegistrationAction()
    {
        if(!check_bitrix_sessid())
        {
            return;
        }

        $post = getPostList();

        $roleID = !is_numeric($post['role']) ? null : (int)$post['role'];
        $cityID = !is_numeric($post['city']) ? null : (int)$post['city'];

        $userForm = new UserForm(
            null,
            $post['name'], $post['lastName'], $post['middleName'],
            $roleID, $post['login'], null, $post['password'], $post['confirmPassword'],
            $post['email'], $post['workingEmail'], $post['phoneNumber'],
            $post['description'],
            $cityID,
            $post['edFormats'],
            $post['subjects'], $post['subjectsPrices'],
            $post['newSubjectsID'], $post['newSubjectsPrices']
        );

        if (\CUser::GetByLogin($userForm->getLogin())->Fetch())
        {
            LocalRedirect('/registration/?err=user_exists');
        }

        $passCheck = Validator::validatePassword((string)$post['password'], (string)$post['passwordConfirm']);
        if ($passCheck !== true)
        {
            LocalRedirect("/registration/?err=$passCheck");
        }
        if (!Validator::validateEmail($post['email']))
        {
            LocalRedirect('/registration/?err=invalid_email');
        }

        if (!Validator::validateNameField($userForm->getName()) ||
            !Validator::validateNameField($userForm->getLastName()) ||
            !Validator::validateNameField($userForm->getMiddleName(), false))
        {
            LocalRedirect('/registration/?err=empty_field');
        }
        if (!Validator::validatePhoneNumber($userForm->getPhoneNumber()))
        {
            LocalRedirect('/registration/?err=invalid_phone');
        }

        if (!Validator::validateSubjectsIDs($userForm->getSubjectsIDs(), false))
        {
            LocalRedirect('/registration/?err=invalid_subject');
        }

        if (!Validator::validateCitiesIDs((array)$userForm->getCityID(), false))
        {
            LocalRedirect('/registration/?err=invalid_city');
        }


        if (!Validator::validateEducationFormatIDs($userForm->getEdFormatsIDs(), false))
        {
            LocalRedirect('/registration/?err=invalid_ed_format');
        }

        if (!Validator::validateRole($userForm->getRoleID()))
        {
            LocalRedirect('/registration/?err=invalid_role');
        }

        $ErrOrUserID = (new UserService($userForm))->addNewEntity();
        if (!is_numeric($ErrOrUserID))
        {
            ShowMessage($ErrOrUserID);
        }
        else
        {
            LocalRedirect("/profile/$ErrOrUserID/");
        }

    }

    public static function LogoutAction()
    {
        session()->clear();
        LocalRedirect('/login/');
    }
}