<?php

namespace Up\Tutortoday\Services;

use Bitrix\Main\UserTable;
use Up\Tutortoday\Model\Tables\CitiesTable;
use Up\Tutortoday\Model\Tables\EducationFormatTable;
use Up\Tutortoday\Model\Tables\SubjectTable;
use Up\Tutortoday\Model\Tables\UserEdFormatTable;
use Up\Tutortoday\Model\Tables\UserSubjectTable;

class SearchService
{
    private string $search;
    private int $numberOfUsers;

    public function __construct(string $userSearch)
    {
        $this->search = $userSearch;
    }

    public function getNumberOfUsers(): int
    {
        return $this->numberOfUsers;
    }

    public function generalSearch()
    {
        $usersByCities = $this->searchInCities();
        $usersByEdFormat = $this->searchInEducationFormats();
        $usersBySubjects = $this->searchInSubjects();
        $result = array_merge($usersByCities, $usersByEdFormat, $usersBySubjects);

        $this->numberOfUsers = count($result);

        return array_unique($result);
    }

    public function searchInCities() : array
    {
        $possibleCities = CitiesTable::query()
            ->setSelect(['ID'])
            ->whereLike('NAME', '%' . $this->search . '%')
            ->fetchCollection();

        if ($possibleCities->count() === 0)
        {
            return [];
        }

        $citiesIDs = [];
        foreach ($possibleCities as $possibleCity)
        {
            $citiesIDs[] = $possibleCity['ID'];
        }

        $users = UserTable::query()
            ->setSelect(['ID'])
            ->whereIn('WORK_CITY', $citiesIDs)
            ->fetchCollection();

        $usersIDs = [];
        foreach ($users as $user)
        {
            $usersIDs[] = $user['ID'];
        }

        $this->numberOfUsers = count($usersIDs);

        return $usersIDs;
    }

    public function searchInEducationFormats() : array
    {
        $possibleEdFormats = EducationFormatTable::query()
            ->setSelect(['ID'])
            ->whereLike('NAME', '%' . $this->search . '%')
            ->fetchCollection();

        if ($possibleEdFormats->count() === 0)
        {
            return [];
        }

        $edFormatsIDs = [];
        foreach ($possibleEdFormats as $possibleEdFormat)
        {
            $edFormatsIDs[] = $possibleEdFormat['ID'];
        }

        $users = UserEdFormatTable::query()
            ->setSelect(['USER_ID'])
            ->whereIn('EDUCATION_FORMAT_ID', $edFormatsIDs)
            ->fetchCollection();

        $usersIDs = [];
        foreach ($users as $user)
        {
            $usersIDs[] = $user['USER_ID'];
        }

        $this->numberOfUsers = count($usersIDs);

        return array_unique($usersIDs);
    }

    public function searchInSubjects() : array
    {
        $possibleSubjects = SubjectTable::query()
            ->setSelect(['ID'])
            ->whereLike('NAME', '%' . $this->search . '%')
            ->fetchCollection();

        if ($possibleSubjects->count() === 0)
        {
            return [];
        }

        $subjectsIDs = [];
        foreach ($possibleSubjects as $possibleSubject)
        {
            $subjectsIDs[] = $possibleSubject['ID'];
        }

        $users = UserSubjectTable::query()
            ->setSelect(['USER_ID'])
            ->whereIn('SUBJECT_ID', $subjectsIDs)
            ->fetchCollection();

        $usersIDs = [];
        foreach ($users as $user)
        {
            $usersIDs[] = $user['USER_ID'];
        }

        $this->numberOfUsers = count($usersIDs);

        return array_unique($usersIDs);
    }
}