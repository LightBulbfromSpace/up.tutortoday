<?php

namespace Up\Tutortoday\Controller;

use Bitrix\Main\Engine\Controller;
use Up\Tutortoday\Model\Services\UserService;

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
            LocalRedirect("/login/?err=empty");
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
    }
}