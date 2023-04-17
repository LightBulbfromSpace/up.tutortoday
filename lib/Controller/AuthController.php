<?php

namespace Up\Tutortoday\Controller;

use Bitrix\Main\Engine\Controller;
use Up\Tutortoday\Model\Validator;
use Up\Tutortoday\Services\UserService;

class AuthController extends Controller
{
    public static function LoginAction()
    {
        if(!check_bitrix_sessid())
        {
            return;
        }
        $post = getPostList();
        if ($post['email'] == null || $post['password'] == null)
        {
            LocalRedirect("/login/?err=empty_field");
        }
        $user = UserService::ValidateUser($post['email'], $post['password']);
        if ($user === null)
        {
            LocalRedirect("/login/?err=auth");
        }
        else
        {
            LocalRedirect("/profile/{$user['ID']}/");
        }
    }

    public static function RegistrationAction()
    {
        if(!check_bitrix_sessid())
        {
            return;
        }

        $post = getPostList();

        $passCheck = Validator::validatePassword($post['password1'], $post['password2']);
        if ($passCheck !== true)
        {
            LocalRedirect("/registration/?err=$passCheck");
        }
        if (!Validator::validateEmail($post['email']))
        {
            LocalRedirect('/registration/?err=invalid_email');
        }

        if (UserService::ValidateUser($post['email'], $post['password1']) !== null)
        {
            LocalRedirect('/registration/?err=user_exists');
        }

        if (!Validator::validateNameField($post['name']) ||
            !Validator::validateNameField($post['surname']) ||
            !Validator::validateNameField($post['middle_name'], false))
        {
            LocalRedirect('/registration/?err=empty_field');
        }
        if (!Validator::validatePhoneNumber($post['phone']))
        {
            LocalRedirect('/registration/?err=invalid_phone');
        }
        if (!Validator::validateSubjectID((int)$post['subject'], false))
        {
            LocalRedirect('/registration/?err=invalid_subject');
        }

        if (!Validator::validateEducationFormatID((int)$post['education_format']))
        {
            LocalRedirect('/registration/?err=invalid_ed_format');
        }

        $userID = UserService::CreateUser(
            $post['name'], $post['surname'], $post['middle_name'],
            $post['password1'], $post['email'], $post['phone'],
            $post['city'], (int)$post['education_format'], (int)$post['subject'],
            $post['description'],
        );
        if ($userID === false)
        {
            LocalRedirect('/registration/?err=unexpected_error');
        }
        LocalRedirect("/profile/$userID/");
    }
}