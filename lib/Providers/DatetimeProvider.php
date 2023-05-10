<?php

namespace Up\Tutortoday\Providers;

use Up\Tutortoday\Model\Tables\FreeTimeTable;
use Up\Tutortoday\Model\Tables\WeekdaysTable;

class DatetimeProvider
{
    private int $userID;
    private int $weekdayID;

    public function __construct(int $userID, int $weekdayID)
    {
        $this->userID = $userID;
        $this->weekdayID = $weekdayID;
    }

    public function getWeekdayTimeByUserID() : array
    {
        try
        {
            $hours = FreeTimeTable::query()
                ->setSelect(['ID', 'START', 'END'])
                ->where('USER_ID', $this->userID)
                ->where('WEEKDAY_ID', $this->weekdayID)
                ->fetchCollection();
        }
        catch (\Exception $e)
        {
            return [];
        }

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
        try
        {
            $weekdays = WeekdaysTable::query()->setSelect(['*'])->fetchCollection();
        }
        catch (\Exception $e)
        {
            return [];
        }
        return $weekdays->count() === 0 ? [] : $weekdays;
    }
}