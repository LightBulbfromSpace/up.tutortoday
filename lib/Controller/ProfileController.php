<?php

namespace Up\Tutortoday\Controller;

use Up\Tutortoday\Model\Tables\ContactsTable;
use Up\Tutortoday\Model\Tables\UserTable;
use Up\Tutortoday\Services\ImagesService;
use Up\Tutortoday\Services\UserService;

class ProfileController
{

    public static function isOwnerOfProfile($userID) : bool
    {
        if (!session()->has('userID'))
        {
            return false;
        }

        if (session()->get('userID') !== $userID)
        {
            return false;
        }

        return true;
    }
}