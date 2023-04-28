<?php

namespace Up\Tutortoday\Services;

use Up\Tutortoday\Model\Tables\CitiesTable;

class LocationService
{

    public static function getAllCities()
    {
        return CitiesTable::query()->setSelect(['*'])->fetchCollection();
    }

    public static function getCityNameByID(?int $cityID)
    {
        if ($cityID === null)
        {
            return null;
        }
        $city = CitiesTable::query()
            ->setSelect(['NAME'])
            ->where('ID', $cityID)
            ->fetchObject();
        if ($city === null)
        {
            return null;
        }
        return $city['NAME'];
    }
}