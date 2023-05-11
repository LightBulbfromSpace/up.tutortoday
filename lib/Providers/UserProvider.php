<?php

namespace Up\Tutortoday\Providers;

use Bitrix\Main\UserTable;
use Up\Tutortoday\Model\Tables\RolesTable;
use Up\Tutortoday\Model\Tables\TelegramTable;
use Up\Tutortoday\Model\Tables\UserDescriptionTable;
use Up\Tutortoday\Model\Tables\UserEdFormatTable;
use Up\Tutortoday\Model\Tables\UserSubjectTable;
use Up\Tutortoday\Model\Tables\VkTable;
use Up\Tutortoday\Services\ImagesService;

class UserProvider
{
    private $observedUserID;
    private array $userIDs = [];
    private int $numOfFetchedUsers = 0;
    private bool $fetchAllAvailableUsers = false;
    private array $roleIDs = [1];
    private bool $onlyUnblocked = false;
    public function __construct(int $userID = 0, array $userIDs = [])
    {
        $this->observedUserID = $userID;
        $this->userIDs = $userIDs;
    }
    public function getUserByID(int $observerID = 0)
    {
        try
        {
            $user = UserTable::query()
                ->setSelect(['*'])
                ->where('ID', $this->observedUserID)
                ->fetchObject();

            if ($user == null)
            {
                return null;
            }

            $roleID = (int)$user['WORK_POSITION'];
            $role = RolesTable::query()
                ->setSelect(['*'])
                ->where('ID', $roleID)
                ->fetchObject();

            if ($role['ID'] == null)
            {
                return null;
            }

            $edFormats = UserEdFormatTable::query()
                ->setSelect(['USER_ID', 'EDUCATION_FORMAT'])
                ->where('USER_ID', $this->observedUserID)
                ->fetchCollection();

            $VKs = VkTable::query()
                ->setSelect(['USER_ID', 'VK_PROFILE'])
                ->where('USER_ID', $this->observedUserID)
                ->fetchCollection();

            $telegrams = TelegramTable::query()
                ->setSelect(['USER_ID', 'TELEGRAM_USERNAME'])
                ->where('USER_ID', $this->observedUserID)
                ->fetchCollection();
            $subjects = UserSubjectTable::query()
                ->setSelect(['USER_ID', 'SUBJECT', 'PRICE'])
                ->where('USER_ID', $this->observedUserID)
                ->fetchCollection();

            $description = UserDescriptionTable::query()
                ->setSelect(['USER_ID', 'DESCRIPTION'])
                ->where('USER_ID', $this->observedUserID)
                ->fetchObject();

            $city = LocationProvider::getCityNameByID((int)$user['WORK_CITY']);

            $photo = (new ImagesService($this->observedUserID))->getProfileImage();
            $feedbacks = [];


            $observerRole = null;

            if ($observerID !== 0)
            {
                $observerRoleID = UserTable::query()
                    ->setSelect(['WORK_POSITION'])
                    ->where('ID', $observerID)
                    ->fetchObject();

                $observerRoleID = (int)$observerRoleID['WORK_POSITION'];

                $observerRole = RolesTable::query()
                    ->setSelect(['*'])
                    ->where('ID', $observerRoleID)
                    ->fetchObject();

                if ($observerRole['NAME'] !== 'tutor')
                {
                    $feedbacks = (new FeedbackProvider($observerID))->getByPage($this->observedUserID, 0);
                }
            }

        }
        catch (\Exception $e)
        {
            return null;
        }

        return [
            'photo' => $photo != null ? $photo['LINK'] : DEFAULT_PHOTO,
            'mainData' => $user,
            'role' => $role,
            'edFormats' => $edFormats,
            'city' => $city,
            'description' => $description['DESCRIPTION'],
            'contacts'=> [
                'phone' => $user['WORK_PHONE'],
                'email' => $user['WORK_MAILBOX'],
                'vk' => $VKs,
                'telegram' => $telegrams,
            ],
            'subjects' => $subjects,
            'feedbacks' => $feedbacks ?? [],
            'observer' => [
                'ID' => $observerID,
                'role' => $observerRole,
            ]
        ];
    }

    public function getUsersByPage(int $offset = 0, int $limit = 50, bool $short = false)
    {
        if ($offset < 0)
        {
            return false;
        }
        if ($this->observedUserID === 0 && $this->userIDs === [] && !$this->fetchAllAvailableUsers)
        {
            $this->numOfFetchedUsers = 0;
            return [];
        }

        $queryForCount = UserTable::query()
            ->setSelect(['ID'])
            ->whereIn('WORK_POSITION', $this->roleIDs)
            ->where('WORK_COMPANY', SITE_NAME);

        $query = UserTable::query()
            ->setSelect(['ID', 'NAME', 'LAST_NAME', 'SECOND_NAME', 'WORK_CITY', 'WORK_POSITION', 'BLOCKED'])
            ->whereIn('WORK_POSITION', $this->roleIDs)
            ->where('WORK_COMPANY', SITE_NAME)
            ->setOrder(['ID' => 'DESC'])
            ->setOffset($offset)
            ->setLimit($limit);

        if (!$this->fetchAllAvailableUsers)
        {
            $queryForCount->whereIn('ID', $this->userIDs);
            $query->whereIn('ID', $this->userIDs);
        }

        if ($this->onlyUnblocked)
        {
            $queryForCount->where('BLOCKED', 'N');
            $query->where('BLOCKED', 'N');
        }

        $users = $query->fetchCollection();

        $this->numOfFetchedUsers = $queryForCount->fetchCollection()->count();

        $fetchedUserIDs = [];
        foreach ($users as $user)
        {
            $fetchedUserIDs[] = $user['ID'];
        }

        $result = [];

        $roles = RolesTable::query()
            ->setSelect(['*'])
            ->whereIn('ID', $this->roleIDs)
            ->fetchCollection();

        foreach ($users as $i => $user)
        {
            $result[$i] = [
                'ID' => $user['ID'],
                'fullName' => [
                    'name' => $user['NAME'],
                    'lastName' => $user['LAST_NAME'],
                    'secondName' => $user['SECOND_NAME'],
                ],
                'blocked' => $user['BLOCKED'],
            ];
            foreach ($roles as $role)
            {
                if ((int)$user['WORK_POSITION'] === (int)$role['ID'])
                {
                    $result[$i]['ROLE'] = [
                        'ID' => $role['ID'],
                        'NAME' => $role['NAME'],
                    ];
                    break;
                }
            }
        }

        if ($short) {
            return $result;
        }

        $descriptions = UserDescriptionTable::query()
            ->setSelect(['USER_ID', 'DESCRIPTION'])
            ->whereIn('USER_ID', $fetchedUserIDs)
            ->fetchCollection();

        $subjects = UserSubjectTable::query()
            ->setSelect(['*', 'SUBJECT'])
            ->whereIn('USER_ID', $fetchedUserIDs)
            ->fetchCollection();

        $edFormats = UserEdFormatTable::query()
            ->setSelect(['*', 'EDUCATION_FORMAT'])
            ->whereIn('USER_ID', $fetchedUserIDs)
            ->fetchCollection();

        $cities = LocationProvider::getAllCities();

        $photos = (new ImagesService())->getProfileImages($this->userIDs);


        //TODO: change this
        $result = [];

        foreach ($users as $i => $user)
        {
            $result[$i] = [
                'ID' => $user['ID'],
                'photo' => $user['PERSONAL_PHOTO'] != null ? $user['PERSONAL_PHOTO'] : DEFAULT_PHOTO,
                'fullName' => [
                    'name' => $user['NAME'],
                    'lastName' => $user['LAST_NAME'],
                    'secondName' => $user['SECOND_NAME'],
                ],
            ];
            foreach ($descriptions as $description)
            {
                if ($user['ID'] === $description['USER_ID'])
                {
                    $result[$i]['description'] = $description['DESCRIPTION'];
                    break;
                }
            }
            foreach ($subjects as $subject)
            {
                if ($user['ID'] === $subject['USER_ID'])
                {
                    $result[$i]['subjects'][] = [
                        'NAME' => $subject['SUBJECT']['NAME'],
                        'ID' => $subject['SUBJECT']['ID'],
                        'PRICE' => $subject['PRICE'],
                    ];
                }
            }
            foreach ($edFormats as $edFormat)
            {
                if ($user['ID'] === $edFormat['USER_ID'])
                {
                    $result[$i]['edFormat'][] = $edFormat['EDUCATION_FORMAT'];
                }
            }
            foreach ($cities as $city)
            {
                if((int)$user['WORK_CITY'] === (int)$city['ID'])
                {
                    $result[$i]['city'] = $city;
                    break;
                }
            }
            foreach ($photos as $photo)
            {
                if ($user['ID'] === $photo['USER_ID'])
                {
                    $result[$i]['photo'] = $photo['LINK'];
                }
            }
        }

        return $result;
    }

    public function getPreferences()
    {
        $preferences = [];

        $edFormats = UserEdFormatTable::query()
            ->setSelect(['EDUCATION_FORMAT_ID'])
            ->where('USER_ID', $this->observedUserID)
            ->fetchCollection();
        foreach ($edFormats as $edFormat)
        {
            $preferences['edFormats'][] = $edFormat['EDUCATION_FORMAT_ID'];
        }
        $subjects = UserSubjectTable::query()
            ->setSelect(['SUBJECT_ID'])
            ->where('USER_ID', $this->observedUserID)
            ->fetchCollection();
        foreach ($subjects as $subject)
        {
            $preferences['subjects'][] = $subject['SUBJECT_ID'];
        }

        $city = UserTable::query()
            ->setSelect(['WORK_CITY'])
            ->where('ID', $this->observedUserID)
            ->fetchObject();

        if ($city['WORK_CITY'] !== '')
        {
            $preferences['cities'][] = $city['WORK_CITY'];
        }
        return $preferences;
    }

    public function getUserRoleByID()
    {
        $result = UserTable::query()
            ->setSelect(['WORK_POSITION'])
            ->where('ID', $this->observedUserID)
            ->fetchObject();

        $roleID = $result['WORK_POSITION'];

        $result = RolesTable::query()
            ->setSelect(['NAME'])
            ->where('ID', $roleID)
            ->fetchObject();

        return [
            'ID' => $roleID,
            'NAME' => $result['NAME'],
        ];
    }

    public static function getUserIDbyLogin($login) : int
    {
        $result = UserTable::query()
            ->setSelect(['ID'])
            ->where('LOGIN', $login)
            ->fetchObject();
        return (int)$result['ID'];
    }

    public function setFetchAllAvailableUsers(bool $fetchAllAvailableUsers): void
    {
        $this->fetchAllAvailableUsers = $fetchAllAvailableUsers;
    }

    /**
     * @param string[] $rolesNames
     */
    public function setRoles(array $rolesNames): void
    {
        $roleIDs = RolesTable::query()
            ->setSelect(['ID'])
            ->whereIn('NAME', $rolesNames)
            ->fetchCollection();
        foreach ($roleIDs as $roleID)
        {
            $this->roleIDs[] = $roleID['ID'];
        }
    }

    public function getOnlyUnblockedUsers(bool $isUnblockedUsers)
    {
        $this->onlyUnblocked = $isUnblockedUsers;
    }

    public function getNumOfFetchedUsers(): int
    {
        return $this->numOfFetchedUsers;
    }
}