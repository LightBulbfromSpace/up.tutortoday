<?php

namespace Up\Tutortoday\Services;

use Up\Tutortoday\Model\Tables\CitiesTable;
use Up\Tutortoday\Model\Tables\EducationFormatTable;
use Up\Tutortoday\Model\Tables\RolesTable;
use Up\Tutortoday\Model\Tables\SubjectTable;
use Up\Tutortoday\Model\Tables\UserSubjectTable;

class EducationService
{
    private array $usersIDs;

    public function __construct(array $usersIDs)
    {
        $this->usersIDs = $usersIDs;
    }

    public static function getEdFormatsPerPage(int $pageFromNull, int $itemsPerPage)
    {
        $offset = $pageFromNull * $itemsPerPage;
        return EducationFormatTable::query()
            ->setSelect(['*'])
            ->setOrder(['ID' => 'DESC'])
            ->setOffset($offset)
            ->setLimit($itemsPerPage)
            ->fetchCollection();
    }

    public static function getSubjectsPerPage(int $pageFromNull, int $itemsPerPage)
    {
        $offset = $pageFromNull * $itemsPerPage;
        return SubjectTable::query()
            ->setSelect(['*'])
            ->setOrder(['ID' => 'DESC'])
            ->setOffset($offset)
            ->setLimit($itemsPerPage)
            ->fetchCollection();
    }

    public static function addNewSubject(string $name)
    {
        $result = SubjectTable::add([
            'NAME' => $name,
        ]);
        return $result->isSuccess();
    }

    public static function addNewEdFormat(string $name)
    {
        $result = EducationFormatTable::add([
            'NAME' => $name,
        ]);
        return $result->isSuccess();
    }

    public static function deleteSubject(int $ID)
    {
        $result = SubjectTable::delete($ID);
        return $result->isSuccess();
    }

    public static function deleteEdFormat(int $ID)
    {
        $result = EducationFormatTable::delete($ID);
        return $result->isSuccess();
    }

    public static function editSubject(int $ID, string $name)
    {
        $result = SubjectTable::update($ID, [
            'NAME' => $name,
        ]);
        return $result->isSuccess();
    }

    public static function editEdFormat(int $ID, mixed $name)
    {
        $result = EducationFormatTable::update($ID, [
            'NAME' => $name,
        ]);
        return $result->isSuccess();
    }

    public function getUsersIDs(): array
    {
        return $this->usersIDs;
    }

    public static function getAllEdFormats()
    {
        return EducationFormatTable::query()->setSelect(['*'])->fetchCollection();
    }

    public static function getAllSubjects()
    {
        return SubjectTable::query()->setSelect(['*'])->fetchCollection();
    }

    public static function getAllRoles()
    {
        return RolesTable::query()->setSelect(['*'])->fetchCollection();
    }

    public function getSubjectsByIDs(array $ID)
    {
        $subject = SubjectTable::query()->setSelect(['*'])->whereIn('ID', $this->usersIDs);
        if ($subject === null)
        {
            return null;
        }
        return $subject->fetchObject();
    }

    public static function getRoleIDbyName(string $name = 'tutor') : int|null
    {
        $role = RolesTable::query()->where('NAME', $name);
        if ($role === null)
        {
            return null;
        }

        return $role->fetchObject()->getID();
    }

    public function getEducationFormatByID()
    {
        $edFormat = EducationFormatTable::query()
            ->setSelect(['*'])
            ->whereIn('ID', $this->usersIDs);
        if ($edFormat === null)
        {
            return null;
        }
        return $edFormat->fetchObject();
    }

    public function deleteUserSubject(mixed $subjectID)
    {
        foreach ($this->usersIDs as $userID)
        {
            $result = UserSubjectTable::delete([
                'USER_ID' => $userID,
                'SUBJECT_ID' => $subjectID,
            ]);
            if (!$result->isSuccess())
            {
                return $result->getErrorMessages();
            }
        }

        return true;
    }
}