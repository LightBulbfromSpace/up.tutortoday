<?php

namespace Up\TutorToday\Controller;

use Bitrix\Main\Engine\Controller;
use Up\TutorToday\Repositories\UserService;

class AuthController extends Controller
{
    public static function LoginAction()
    {
        if(!check_bitrix_sessid())
        {
            return;
        }
        $post = getPostList();
        $user = UserService::ValidateUser($post['email'], $post['password']);
        if ($user === null)
        {

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