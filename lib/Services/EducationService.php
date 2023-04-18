<?php

namespace Up\Tutortoday\Services;

use Up\Tutortoday\Model\Tables\EducationFormatTable;
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
    public static function getSubjectByID(int $ID) : array|null
    {
        return null;
    }

    public static function getEducationFormatByID(int $ID) : array|null
    {
        return [];
    }
}