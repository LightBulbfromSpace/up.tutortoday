<?php

namespace Up\Tutortoday\Services;

use Up\Tutortoday\Model\Tables\FreeTimeTable;
use Up\Tutortoday\Model\Tables\WeekdaysTable;

class DatetimeService
{
    public static function getWeekdayTimeByUserID(int $userID, int $weekdayID) {
        $hours = FreeTimeTable::query()
            ->setSelect(['START', 'END'])
            ->where('USER_ID', $userID)
            ->where('WEEKDAY_ID', $weekdayID)
            ->fetchCollection();
        $result = [];
        foreach ($hours as $hour)
        {
            $result[] = ['start' => $hour['START']->format('H:i'), 'end' => $hour['END']->format('H:i')];
        }
        return json_encode($result);
    }

    public static function getAllWeekdays()
    {
        $weekdays = WeekdaysTable::query()->setSelect(['*'])->fetchCollection();
        return $weekdays === null ? false : $weekdays;
    }
}