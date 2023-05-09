<?php

namespace Up\Tutortoday\Services;

use Bitrix\Main\Type\DateTime;
use Up\Tutortoday\Model\Tables\FreeTimeTable;
use Up\Tutortoday\Model\Tables\WeekdaysTable;

class DatetimeService
{
    public static function getWeekdayTimeByUserID(int $userID, int $weekdayID)
    {
        $hours = FreeTimeTable::query()
            ->setSelect(['ID', 'START', 'END'])
            ->where('USER_ID', $userID)
            ->where('WEEKDAY_ID', $weekdayID)
            ->fetchCollection();
        $result = [];
        foreach ($hours as $hour)
        {
            $result[] = [
                'ID' => $hour['ID'],
                'start' => $hour['START']->format('H:i'),
                'end' => $hour['END']->format('H:i')
            ];
        }
        return $result;
    }

    public static function getAllWeekdays()
    {
        $weekdays = WeekdaysTable::query()->setSelect(['*'])->fetchCollection();
        return $weekdays === null ? false : $weekdays;
    }

    public static function createTime(int $userID, int $weekdayID, array $timeToAdd)
    {
        return FreeTimeTable::add([
            'USER_ID' => $userID,
            'WEEKDAY_ID' => $weekdayID,
            'START' => DateTime::createFromPhp(\DateTime::createFromFormat('H:i', $timeToAdd['timeFrom'])),
            'END' => DateTime::createFromPhp(\DateTime::createFromFormat('H:i', $timeToAdd['timeTo'])),
        ]);
    }

    public static function deleteTime(mixed $timeID)
    {
        return FreeTimeTable::delete($timeID);
    }
}