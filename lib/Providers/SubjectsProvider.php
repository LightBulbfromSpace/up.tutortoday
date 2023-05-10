<?php

namespace Up\Tutortoday\Providers;

use Up\Tutortoday\Model\Tables\SubjectTable;

class SubjectsProvider
{
    public static function getAllSubjects()
    {
        return SubjectTable::query()->setSelect(['*'])->fetchCollection();
    }

    public static function getNumberOfAllSubjects() {
        return SubjectTable::query()
            ->setSelect(['ID'])
            ->fetchCollection()
            ->count();
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

    public static function getSubjectByID(int $ID)
    {
        $subject = SubjectTable::query()->setSelect(['*'])->where('ID', $ID);
        if ($subject === null)
        {
            return null;
        }
        return $subject->fetchObject();
    }
}