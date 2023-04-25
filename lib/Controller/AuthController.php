<?php

namespace Up\Tutortoday\Controller;

use Bitrix\Main\Engine\Controller;
use Up\Tutortoday\Model\FormObjects\UserForm;
use Up\Tutortoday\Model\FormObjects\UserRegisterForm;
use Up\Tutortoday\Model\Validator;
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

        $USER = new \CUser();
        $result = $USER->Login($post['login'], $post['password']);
        if ($result !== true)
        {
            LocalRedirect("/login/?err=auth");
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
        $userForm = new UserRegisterForm($post);

        if (\CUser::GetByLogin($userForm->getLogin())->Fetch())
        {
            LocalRedirect('/registration/?err=user_exists');
        }

        $passCheck = Validator::validatePassword($post['password'], $post['passwordConfirm']);
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

        if (!Validator::validateEducationFormatID($userForm->getEdFormatID()))
        {
            LocalRedirect('/registration/?err=invalid_ed_format');
        }

        $ErrOrUserID = UserService::CreateUser($userForm);
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
        LocalRedirect('/');
    }
}