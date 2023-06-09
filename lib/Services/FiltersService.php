<?php

namespace Up\Tutortoday\Services;

use Bitrix\Main\Entity\Query;
use Bitrix\Main\Type\ParameterDictionary;
use Bitrix\Main\UserTable;
use Up\Tutortoday\Model\Tables\EducationFormatTable;
use Up\Tutortoday\Model\Tables\RolesTable;
use Up\Tutortoday\Model\Tables\SubjectTable;
use Up\Tutortoday\Model\Tables\UserEdFormatTable;
use Up\Tutortoday\Model\Tables\UserSubjectTable;

class FiltersService
{
    private int $numberOfUsersByFilters = 0;
    private bool $isFilterUsed;
    private array $educationFormatIDs = [];
    private array $subjectIDs = [];
    private array $citiesIDs = [];
    private int $minPrice;
    private int $maxPrice;


    public function __construct(array $dict)
    {
        $this->educationFormatIDs = $dict['edFormats'] != null ? $dict['edFormats'] : [];
        $this->subjectIDs = $dict['subjects'] != null ? $dict['subjects'] : [];
        $this->citiesIDs = $dict['cities'] != null ? $dict['cities'] : [];
        $this->minPrice = $dict['minPrice'] != null || $dict['minPrice'] != '' ? (int)$dict['minPrice'] : 0;
        $this->maxPrice = $dict['maxPrice'] != null || $dict['maxPrice'] != ''  ? (int)$dict['maxPrice'] : PHP_INT_MAX;
    }

    public function getNumberOfFilteredUsers(): int
    {
        return $this->numberOfUsersByFilters;
    }

    public function filterTutors() : array
	{
        $tutorIDsBySubject = [];
        if ($this->subjectIDs !== [] || $this->minPrice > 0 || $this->maxPrice < PHP_INT_MAX) {

            $tutorIDsBySubjectRaw = UserSubjectTable::query()
                ->setSelect(['USER_ID', 'PRICE'])
                ->setOrder(['USER_ID' => 'DESC'])
                ->whereIn('SUBJECT_ID', $this->subjectIDs)
                ->whereBetween('PRICE', $this->minPrice, $this->maxPrice)
                ->fetchCollection();

            foreach ($tutorIDsBySubjectRaw as $ID)
            {
                $tutorIDsBySubject[] = $ID['USER_ID'];
            }
        }

        $tutorIDsBySubject = $tutorIDsBySubject !== [] ? array_unique($tutorIDsBySubject): [];

        $tutorIDsByEdFormat = [];
        if ($this->educationFormatIDs !== [])
        {
            $tutorIDsByEdFormatRaw = UserEdFormatTable::query()
                ->setSelect(['USER_ID'])
                ->whereIn('EDUCATION_FORMAT_ID', $this->educationFormatIDs)
                ->setOrder(['USER_ID' => 'DESC'])
                ->fetchCollection();

            foreach ($tutorIDsByEdFormatRaw as $ID)
            {
                $tutorIDsByEdFormat[] = $ID['USER_ID'];
            }
        }

        $tutorIDsByCities = [];
        if ($this->citiesIDs !== [])
        {
            $tutorIDsByCitiesRaw = UserTable::query()
                ->setSelect(['ID'])
                ->setOrder(['ID' => 'DESC'])
                ->whereIn('WORK_CITY', $this->citiesIDs)
                ->fetchCollection();

            foreach ($tutorIDsByCitiesRaw as $ID)
            {
                $tutorIDsByCities[] = $ID['ID'];
            }
        }

        $intersections = [];

        if (($this->subjectIDs !== []  || $this->minPrice > 0 || $this->maxPrice < PHP_INT_MAX)
            && $this->citiesIDs !== [] )
        {
            $intersections[] = array_intersect($tutorIDsBySubject, $tutorIDsByCities);
        }

        if ($this->educationFormatIDs !== [] && $this->citiesIDs !== [])
        {
            $intersections[] = array_intersect($tutorIDsByEdFormat, $tutorIDsByCities);
        }

        if (($this->subjectIDs !== [] || $this->minPrice > 0 || $this->maxPrice < PHP_INT_MAX)
            && $this->educationFormatIDs !== [])
        {
            $intersections[] = array_intersect($tutorIDsBySubject, $tutorIDsByEdFormat);
        }

        $tutorsIDs = [];

        if ($intersections === [])
        {
            if ($tutorIDsBySubject !== [])
            {
                $tutorsIDs = $tutorIDsBySubject;
            }
            if ($tutorIDsByEdFormat !== [])
            {
                $tutorsIDs = $tutorIDsByEdFormat;
            }
            if ($tutorIDsByCities !== [])
            {
                $tutorsIDs = $tutorIDsByCities;
            }
        }
        else
        {
            $tutorsIDs = array_intersect(...$intersections);
        }

        $this->isFilterUsed = true;

        $this->numberOfUsersByFilters = count($tutorsIDs);
        return $tutorsIDs;
	}

    public function isFilterUsed(): bool
    {
        return $this->isFilterUsed;
    }
}