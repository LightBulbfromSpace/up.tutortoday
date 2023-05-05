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

    public static function getCitiesPerPage(int $pageFromNull, int $itemsPerPage)
    {
        $offset = $pageFromNull * $itemsPerPage;
        return CitiesTable::query()
            ->setSelect(['*'])
            ->setOrder(['ID' => 'DESC'])
            ->setOffset($offset)
            ->setLimit($itemsPerPage)
            ->fetchCollection();
    }

    public static function deleteCity(int $ID)
    {
        $result = CitiesTable::delete($ID);
        return $result->isSuccess();
    }

    public static function addNewCity(string $name)
    {
        $result = CitiesTable::add([
            'NAME' => $name,
        ]);
        return $result->isSuccess();
    }

    public static function editCity(int $ID, mixed $name)
    {
        $result = CitiesTable::update($ID, [
            'NAME' => $name,
        ]);
        return $result->isSuccess();
    }

    public static function getNumberOfAllCities()
    {
        return CitiesTable::query()
            ->setSelect(['ID'])
            ->fetchCollection()
            ->count();
    }
}