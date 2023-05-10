<?php

namespace Up\Tutortoday\Providers;

use Up\Tutortoday\Model\Tables\EducationFormatTable;

class EdFormatsProvider
{
    public static function getAllEdFormats()
    {
        return EducationFormatTable::query()->setSelect(['*'])->fetchCollection();
    }

    public static function getNumberOfAllEdFormats()
    {
        return EducationFormatTable::query()
            ->setSelect(['ID'])
            ->fetchCollection()
            ->count();
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

    public static function getEducationFormatByID(int $edFormatID)
    {
        $edFormat = EducationFormatTable::query()
            ->setSelect(['*'])
            ->whereIn('ID', $edFormatID);
        if ($edFormat === null)
        {
            return null;
        }
        return $edFormat->fetchObject();
    }
}