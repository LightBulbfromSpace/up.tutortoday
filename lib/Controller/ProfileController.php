<?php

namespace Up\Tutortoday\Controller;

class ProfileController
{
    // Photo
    // Full name
    // Contacts (email, phone, telegram, vk)
    // Subject
    // Education format
    // City
    // Role
    // Subjects
    public static function getProfileData($userID)
    {
        if (!session()->has('userID'))
        {
            LocalRedirect("/login/");
        }

        if (session()->get('userID') !== $userID)
        {
            LocalRedirect("/login/");
        }


    }
}