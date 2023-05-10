<?php

namespace Up\Tutortoday\Model\FormObjects;

class WeekdayTimeForm extends BaseEntityForm
{
    private ?int $weekdayID;
    private ?string $timeFrom;
    private ?string $timeTo;

    public function __construct(
        ?int $ID = null,
        ?int $weekdayID = null,
        ?string $timeFrom = null,
        ?string $timeTo = null)
    {
        parent::__construct($ID);
        $this->weekdayID = $weekdayID;
        $this->timeFrom= $timeFrom;
        $this->timeTo = $timeTo;
    }

    public function getWeekdayID(): ?int
    {
        return $this->weekdayID;
    }
    public function getTimeFrom(): ?string
    {
        return $this->timeFrom;
    }

    public function getTimeTo(): ?string
    {
        return $this->timeTo;
    }

}