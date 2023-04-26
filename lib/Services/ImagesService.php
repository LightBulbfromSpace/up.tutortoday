<?php

namespace Up\Tutortoday\Services;

use Bitrix\Main\File\Image;
use Bitrix\Main\FileTable;
use Bitrix\Main\UserTable;
use Up\Tutortoday\Model\Tables\ProfileImagesTable;

class ImagesService
{
    //TODO: add in template or component <?= CFile::InputFile("IMAGE_ID", 20, $str_IMAGE_ID); ? >

    public static function saveProfileImage(int $userID, $file)
    {
        \CFile::IsImage($file);

        //TODO: image name must be a hash!
    }

    public static function getProfileImage(int $userID)
    {
        $photoData = UserTable::query()
            ->setSelect(['PERSONAL_PHOTO'])
            ->where('ID', $userID)
            ->fetchObject()
            ->getPersonalPhoto();
        if ($photoData == null)
        {
            return DEFAULT_PHOTO;
        }
        return $photoData;
    }

    // should be called before saving
    public static function editProfileImage($photo)
    {

    }
}