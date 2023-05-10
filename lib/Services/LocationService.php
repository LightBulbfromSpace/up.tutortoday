<?php

namespace Up\Tutortoday\Services;

use Up\Tutortoday\Model\FormObjects\CityForm;
use Up\Tutortoday\Model\Tables\CitiesTable;

class LocationService extends BaseService
{
    public function __construct(CityForm $city)
    {
        parent::__construct(new CitiesTable(), $city);
    }
}