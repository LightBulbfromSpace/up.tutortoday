<?php

namespace Up\Tutortoday\Services;

use Bitrix\Main\File\Image;
use Bitrix\Main\FileTable;
use Bitrix\Main\UserTable;
use DirectoryIterator;
use Up\Tutortoday\Model\Tables\ProfileImagesTable;

//error_reporting(E_ALL); // or E_STRICT
//ini_set("display_errors",1);
//ini_set("memory_limit","1024M");

class ImagesService
{
    private const STORAGE_ROOT = MODULE_ROOT . '/images/';
    private int $userID;
    private ErrorService $errors;

    private string $extension;

    public function getErrors(): ErrorService
    {
        return $this->errors;
    }

    public function __construct(int $userID = 0)
    {
        $this->errors = new ErrorService('');
        $this->userID = $userID;
    }

    protected function editProfileImage(string $srcFilepath, string $destFilepath) : bool|string
    {
        $info = getimagesize($srcFilepath);
        if ($this->extension === 'png')
        {
            $img = imagecreatefrompng($srcFilepath);
        }
        else
        {
            $img = imagecreatefromjpeg($srcFilepath);
        }
        $width = $info[0]; $height = $info[1];
        if ($width > $height)
        {
            $difference = (int)(($width - $height) / 2);
            $rectangle = [
                'x' => $difference, 'y' => 0,
                'width' => $height, 'height' => $height,
            ];
        }
        elseif ($width < $height)
        {
            $difference = (int)(($height - $width) / 2);
            $rectangle = [
                'x' => 0, 'y' => $difference,
                'width' => $width, 'height' => $width,
            ];
        }
        else
        {
            $rectangle = [
                'x' => 0, 'y' => 0,
                'width' => $width, 'height' => $width,
            ];
        }

        $img = imagecrop($img, $rectangle);
        if ($img === false)
        {
            return false;
        }

        if ($this->extension === 'png')
        {
            $result = imagepng($img, $destFilepath, 5);
        }
        else
        {
            $result = imagejpeg($img, $destFilepath, 75);
        }
        if (!$result)
        {
            return false;
        }

        return true;
    }

    protected function syncDBWithStorage($photoEntities)
    {
        $existingEntities = [];
        foreach ($photoEntities as $photoEntity)
        {
            if (!file_exists(MODULE_ROOT . '/../../../' . $photoEntity['LINK']))
            {
                ProfileImagesTable::delete($photoEntity['ID']);
            }
            else
            {
                $existingEntities[] = $photoEntity;
            }
        }
        return $existingEntities;
    }

    public function saveImageToStorage($photo)
    {
        if ($photo['type'] !== 'image/png' && $photo['type'] !== 'image/jpg' && $photo['type'] !== 'image/jpeg')
        {
            $this->errors->addError('err_invalid_type');
            return null;
        }

        if (!file_exists(self::STORAGE_ROOT . $this->userID)) {
            mkdir(self::STORAGE_ROOT . $this->userID, 0777, true);
        }

        if (!file_exists(self::STORAGE_ROOT . $this->userID . '/tmp/')) {
            mkdir(self::STORAGE_ROOT  . $this->userID . '/tmp/', 0777, true);
        }

        $name = (new \DateTime())->getTimestamp();
        $this->extension = substr($photo['type'], 6);

        $filepathTmp = self::STORAGE_ROOT . "$this->userID/tmp/$name.$this->extension";
        $filepath = self::STORAGE_ROOT . "/$this->userID/$name.$this->extension";

        $result = move_uploaded_file($photo['tmp_name'], $filepathTmp);
        if (!$result)
        {
            $this->errors->addError('err_move_tmp');
            return null;
        }

        $isImageEdited = $this->editProfileImage($filepathTmp, $filepath);
        if (!$isImageEdited)
        {
            $this->errors->addError('err_img_edit');
            return null;
        }

        $deleteResult = unlink($filepathTmp);
        if (!$deleteResult)
        {
            $this->errors->addError('err_img_delete');
            return null;
        }


        return str_replace(MODULE_ROOT, '/local/modules/up.tutortoday', $filepath);
    }

    public function getProfileImage() {
        $photo =  ProfileImagesTable::query()
            ->setSelect(['*'])
            ->where('USER_ID', $this->userID)
            ->fetchObject();
        return $this->syncDBWithStorage([$photo])[0];
    }

    public function getProfileImages(array $userIDs)
    {
        $photos =  ProfileImagesTable::query()
            ->setSelect(['*'])
            ->whereIn('USER_ID', $userIDs)
            ->fetchCollection();

        return $this->syncDBWithStorage($photos);
    }

    public function clearTrash(string $profilePhoto)
    {
        $dir = new DirectoryIterator(self::STORAGE_ROOT . $this->userID);
        foreach ($dir as $fileinfo) {
            if ($fileinfo->isDot() || $fileinfo->getFilename() === $profilePhoto) {
                continue;
            }
            unlink($dir->getPath() . '/' . $fileinfo->getFilename());
        }
    }
}