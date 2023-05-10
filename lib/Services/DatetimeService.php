<?php

namespace Up\Tutortoday\Services;

use Bitrix\Main\Type\DateTime;
use Up\Tutortoday\Model\FormObjects\WeekdayTimeForm;
use Up\Tutortoday\Model\Tables\FreeTimeTable;
use Up\Tutortoday\Model\Tables\WeekdaysTable;

class DatetimeService extends BaseService
{
    private ?int $userID;

    public function __construct(?int $userID, WeekdayTimeForm $timeForm)
    {
        parent::__construct(new FreeTimeTable(), $timeForm);
        $this->userID = $userID;
    }

    public function addNewEntity() : ?int
    {
        try
        {
            $result = FreeTimeTable::add([
                'USER_ID' => $this->userID,
                'WEEKDAY_ID' => $this->entity->getWeekdayID(),
                'START' => DateTime::createFromPhp(
                    \DateTime::createFromFormat('H:i', $this->entity->getTimeFrom())
                ),
                'END' => DateTime::createFromPhp(
                    \DateTime::createFromFormat('H:i', $this->entity->getTimeTo())
                ),
            ]);
        }
        catch (\Exception $e)
        {
            return null;
        }

        return $result->isSuccess() ? $this->userID : null;
    }
}