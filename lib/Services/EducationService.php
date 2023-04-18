<?php

namespace Up\Tutortoday\Services;

use Up\Tutortoday\Model\Tables\EducationFormatTable;
use Up\Tutortoday\Model\Tables\RolesTable;
use Up\Tutortoday\Model\Tables\SubjectTable;

class EducationService
{
    public static function getAllEdFormats()
    {
        return EducationFormatTable::query()->setSelect(['*'])->fetchCollection();
    }

    public static function getAllSubjects()
    {
        return SubjectTable::query()->setSelect(['*'])->fetchCollection();
    }
    public static function getSubjectByID(int $ID)
    {
        $subject = SubjectTable::query()->setSelect(['*'])->where('ID', $ID);
        if ($subject === null)
        {
            return null;
        }
        return $subject->fetchObject();
    }

    public static function getRoleIDbyName(string $name) : int|null
    {
        $role = RolesTable::query()->where('NAME', $name);
        if ($role === null)
        {
            return null;
        }
        return $role->fetchObject()->getID();
    }

    public static function getEducationFormatByID(int $ID)
    {
        $edFormat = EducationFormatTable::query()->setSelect(['*'])->where('ID', $ID);
        if ($edFormat === null)
        {
            return null;
        }
        return $edFormat->fetchObject();
    }
}