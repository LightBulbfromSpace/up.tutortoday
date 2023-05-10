<?php

namespace Up\Tutortoday\Providers;

use Up\Tutortoday\Model\Tables\CitiesTable;

class LocationProvider
{

    public static function getAllCities()
    {
        try
        {
            $allCities = CitiesTable::query()->setSelect(['*'])->fetchCollection();
        }
        catch (\Exception $e)
        {
            return [];
        }
        return $allCities->count() === 0 ? [] : $allCities;
    }

    public static function getCityNameByID(?int $cityID) : ?string
    {
        try
        {
            $city = CitiesTable::query()
                ->setSelect(['NAME'])
                ->where('ID', $cityID)
                ->fetchObject();
        }
        catch (\Exception $e)
        {
            return null;
        }

        return $city == null ? null : $city['NAME'];
    }

    public static function getCitiesPerPage(int $pageFromNull, int $itemsPerPage)
    {
        $offset = $pageFromNull * $itemsPerPage;

        try
        {
            $cities = CitiesTable::query()
                ->setSelect(['*'])
                ->setOrder(['ID' => 'DESC'])
                ->setOffset($offset)
                ->setLimit($itemsPerPage)
                ->fetchCollection();
        }
        catch (\Exception $e)
        {
            return [];
        }

        return $cities->count() === 0 ? [] : $cities;
    }

    public static function getNumberOfAllCities() : int
    {
        try
        {
            $numOfCities = CitiesTable::query()
                ->setSelect(['ID'])
                ->fetchCollection()
                ->count();
        }
        catch (\Exception $e)
        {
            return 0;
        }
        return $numOfCities;
    }
}