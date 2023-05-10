<?php

namespace Up\Tutortoday\Services;

use Bitrix\Main\File\Image;
use Bitrix\Main\FileTable;
use Bitrix\Main\UserTable;
use DirectoryIterator;
use Up\Tutortoday\Model\Tables\ProfileImagesTable;

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

    public static function cutPathToProjectRoot($filepath)
    {
        return str_replace(MODULE_ROOT, '/local/modules/up.tutortoday', $filepath);
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

    protected function syncDBWithStorage()
    {
        $photoEntities = ProfileImagesTable::query()
            ->setSelect(['ID', 'LINK'])
            ->where('USER_ID', $this->userID)
            ->fetchCollection();
        foreach ($photoEntities as $photoEntity)
        {
            if (!file_exists(MODULE_ROOT . '/../../../' . $photoEntity['LINK']))
            {
                ProfileImagesTable::delete($photoEntity['ID']);
            }
        }
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

        if (!file_exists(self::STORAGE_ROOT . $this->userID . '/avatar/')) {
            mkdir(self::STORAGE_ROOT  . $this->userID . '/avatar/', 0777, true);
        }

        $name = (new \DateTime())->getTimestamp();
        $this->extension = substr($photo['type'], 6);

        $filepathTmp = self::STORAGE_ROOT . "$this->userID/tmp/$name.$this->extension";
        $filepath = self::STORAGE_ROOT . "/$this->userID/$name.$this->extension";

        //return [$photo['tmp_name'], $filepath];
        $result = move_uploaded_file($photo['tmp_name'], $filepath);
        if (!$result)
        {
            $this->errors->addError('err_move_tmp');
            return null;
        }

        $isImageEdited = $this->editProfileImage($filepath, $filepathTmp);
        if (!$isImageEdited)
        {
            $this->errors->addError('err_img_edit');
            return null;
        }

        $deleteResult = unlink($filepath);
        if (!$deleteResult)
        {
            $this->errors->addError('err_img_delete');
            return null;
        }


        return self::cutPathToProjectRoot($filepathTmp);
    }

    public function getAvatarDir()
    {
        return self::STORAGE_ROOT . $this->userID . '/avatar/';
    }

    public function getProfileImage() {
        $this->syncDBWithStorage();
        $this->clearTrash($this->getTmpDir());
        return ProfileImagesTable::query()
            ->setSelect(['*'])
            ->where('USER_ID', $this->userID)
            ->fetchObject();
    }

    public function getProfileImages(array $userIDs)
    {
        $photos =  ProfileImagesTable::query()
            ->setSelect(['*'])
            ->whereIn('USER_ID', $userIDs)
            ->fetchCollection();
        if ($photos->count() === 0)
        {
            return null;
        }

        return $photos;
    }

    public function clearTrash(string $absoluteDirPath, array $exceptionFileNames = [])
    {
        if (!file_exists($absoluteDirPath) && !is_dir($absoluteDirPath))
        {
            return;
        }
        $dir = new DirectoryIterator($absoluteDirPath);
        foreach ($dir as $fileinfo) {
            if ($fileinfo->isDot() || in_array($fileinfo->getFilename(), $exceptionFileNames))
            {
                continue;
            }
            unlink($dir->getPath() . '/' . $fileinfo->getFilename());
        }
    }

    public function saveProfileImage(string $name)
    {
        $avatarDir = self::STORAGE_ROOT . $this->userID . '/avatar/';
        $tmpDir = self::STORAGE_ROOT . $this->userID . '/tmp/';
        if (!file_exists($avatarDir)) {
            mkdir($avatarDir, 0766, true);
        }

        $result = rename($tmpDir . $name, $avatarDir . $name);
        if (!$result)
        {
            $this->errors->addError('err_confirm_tmp');
        }

        $link = self::cutPathToProjectRoot($avatarDir . $name);
        $photo = $this->getProfileImage();
        if ($photo !== null) {
            ProfileImagesTable::update($photo['ID'], [
                'LINK' => $link,
            ]);
        }
        else
        {
            ProfileImagesTable::add([
                'USER_ID' => $this->userID,
                'LINK' => $link,
            ]);
        }
        return $avatarDir . $name;
    }

    public function getTmpFiles()
    {
        $result = [];
        $tmpDir = self::STORAGE_ROOT . $this->userID . '/tmp/';
        $dir = new DirectoryIterator($tmpDir);
        foreach ($dir as $file)
        {
            if ($file->isDot())
            {
                continue;
            }
            $result[] = $file->getPath() . '/' . $file->getFilename();
        }
        return $result;
    }

    public function getLastTmpFile()
    {
        $files = $this->getTmpFiles();
        $maxTimestamp = 0;
        $lastFile = null;
        foreach ($files as $file)
        {
            $creationTime = filectime($file);
            if ($creationTime > $maxTimestamp)
            {
                $maxTimestamp = $creationTime;
                $lastFile = $file;
            }
        }
        return $lastFile;
    }

    public function getTmpDir()
    {
        return self::STORAGE_ROOT . $this->userID . '/tmp/';
    }

    public function deleteProfilePhoto()
    {
        $photos = ProfileImagesTable::query()
            ->setSelect(['ID'])
            ->where('USER_ID', $this->userID)
            ->fetchCollection();
        foreach ($photos as $photo)
        {
            ProfileImagesTable::delete($photo['ID']);
        }

        return true;
    }
}