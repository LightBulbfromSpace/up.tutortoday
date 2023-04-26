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
    private mixed $filteredTutors;
    private bool $isFilterUsed;

    private array $educationFormatIDs;
    private array $subjectIDs;
    private array $cityValues;

    private int $minPrice;
    private int $maxPrice;

    public function __construct(ParameterDictionary $dict)
    {
        $this->educationFormatIDs = $dict['edFormats'] != null ? $dict['edFormats'] : [];
        $this->subjectIDs = $dict['subjects'] != null ? $dict['subjects'] : [];
        $this->cityValues = $dict['city'] != null ? $dict['city'] : [];
        $this->minPrice = $dict['minPrice'] != null ? $dict['minPrice'] : 0;
        $this->maxPrice = $dict['maxPrice'] != null ? $dict['maxPrice'] : PHP_INT_MAX;
    }

    public function filterTutors(int $offset = 0, int $limit = 50) : void
	{
//		$tutors = UserTable::query()
//			->whereBetween('PRICE', $arrFilters['PRICE_MIN'], $arrFilters['PRICE_MAX'])
//			->whereIn("EducationFormatTable" . "NAME", $arrFilters['FORMATS'])
//			->whereIn("SubjectTable" . "NAME", $arrFilters['SUBJECTS']);


        $tutorIDsBySubject = null;
        $tutorIDsBySubjectRaw = [];
        if ($this->subjectIDs != null)
        {
            $tutorIDsBySubjectRaw = UserSubjectTable::query()
                ->setSelect(['USER_ID', 'PRICE'])
                ->whereIn('SUBJECT_ID', $this->subjectIDs)
                //->whereBetween('PRICE', $this->minPrice, $this->maxPrice)
                ->fetchCollection();
        }

        foreach ($tutorIDsBySubjectRaw as $ID)
        {
            $tutorIDsBySubject[] = $ID['USER_ID'];
        }

        $tutorIDsByEdFormat = null;
        $tutorIDsByEdFormatRaw = [];
        var_dump($this->educationFormatIDs);
        if ($this->educationFormatIDs != null)
        {
            $tutorIDsByEdFormatRaw = UserEdFormatTable::query()
                ->setSelect(['USER_ID'])
                ->whereIn('EDUCATION_FORMAT_ID', $this->subjectIDs)
                ->fetchCollection();
        }

        foreach ($tutorIDsByEdFormatRaw as $ID)
        {
            $tutorIDsBySubject[] = $ID['USER_ID'];
        }

        var_dump($tutorIDsBySubject, $tutorIDsByEdFormat);
        if ($tutorIDsBySubject !== null && $tutorIDsByEdFormat !== null)
        {
            $tutorsIDs = array_intersect($tutorIDsBySubject, $tutorIDsByEdFormat);
        }
        else
        {
            $tutorsIDs = $tutorIDsBySubject === [] ? $tutorIDsByEdFormat : $tutorIDsBySubject;
        }

        $this->isFilterUsed = true;

        if ($tutorsIDs == null)
        {
            $this->filteredTutors = [];
            return;
        }
        $tutorsIDs = array_slice($tutorsIDs, $offset, $limit);

        $tutorRoleID = RolesTable::query()
            ->setSelect(['ID'])
            ->where('NAME', 'tutor')
            ->fetchObject();
        $this->filteredTutors = UserService::getUserMainInfoWithDescByIDs($tutorsIDs, $tutorRoleID['ID']);
	}
    public function getTutorsByName($name)
	{
		$tutors = UserTable::query()->setSelect(['*'])
			->where(Query::expr()->concat("SURNAME", "NAME", "MIDDLE_NAME"), 'like', $name);

        $this->isFilterUsed = true;
		$this->filteredTutors = $tutors->fetchCollection();
	}

    public function getFilteredTutors() : mixed
    {
        return $this->filteredTutors;
    }

    public function isFilterUsed(): bool
    {
        return $this->isFilterUsed;
    }
}