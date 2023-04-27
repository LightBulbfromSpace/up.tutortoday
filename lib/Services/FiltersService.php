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
    private int $numberOfUsersByFilters = 0;


    private bool $isFilterUsed;
    private array $educationFormatIDs;
    private array $subjectIDs;
    private array $cityValues;
    private $minPrice;

    private $maxPrice;
    public function __construct(array $dict)
    {
        $this->educationFormatIDs = $dict['edFormats'] != null ? $dict['edFormats'] : [];
        $this->subjectIDs = $dict['subjects'] != null ? $dict['subjects'] : [];
        $this->cityValues = $dict['city'] != null ? $dict['city'] : [];
        $this->minPrice = $dict['minPrice'] != null || $dict['minPrice'] != '' ? $dict['minPrice'] : 0;
        $this->maxPrice = $dict['maxPrice'] != null || $dict['maxPrice'] != ''  ? $dict['maxPrice'] : PHP_INT_MAX;
    }

    public function getNumberOfFilteredUsers(): int
    {
        return $this->numberOfUsersByFilters;
    }

    public function filterTutors(int $offset = 0, int $limit = 50) : void
	{
        $tutorIDsBySubject = null;
        $tutorIDsBySubjectRaw = UserSubjectTable::query()
            ->setSelect(['USER_ID', 'PRICE'])
            ->setOrder(['USER_ID' => 'DESC']);

        if ($this->subjectIDs !== []) {

            $tutorIDsBySubjectRaw = $tutorIDsBySubjectRaw->whereIn('SUBJECT_ID', $this->subjectIDs);
        }

        if ($this->minPrice !== null || $this->maxPrice !== null)
        {
            $tutorIDsBySubjectRaw = $tutorIDsBySubjectRaw->whereBetween('PRICE', $this->minPrice, $this->maxPrice);
        }

        $tutorIDsBySubjectRaw = $tutorIDsBySubjectRaw->fetchCollection();

        foreach ($tutorIDsBySubjectRaw as $ID)
        {
            $tutorIDsBySubject[] = $ID['USER_ID'];
        }

        $tutorIDsBySubject = $tutorIDsBySubject != null ? array_unique($tutorIDsBySubject): [];

        $tutorIDsByEdFormat = null;
        $tutorIDsByEdFormatRaw = [];

        if ($this->educationFormatIDs !== [])
        {
            $tutorIDsByEdFormatRaw = UserEdFormatTable::query()
                ->setSelect(['USER_ID'])
                ->whereIn('EDUCATION_FORMAT_ID', $this->educationFormatIDs)
                ->setOrder(['USER_ID' => 'DESC'])
                ->fetchCollection();
        }

        foreach ($tutorIDsByEdFormatRaw as $ID)
        {
            $tutorIDsByEdFormat[] = $ID['USER_ID'];
        }

        if ($tutorIDsBySubject != null && $tutorIDsByEdFormat != null)
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

        $this->numberOfUsersByFilters = count($tutorsIDs);
        $tutorsIDs = array_slice($tutorsIDs, $offset, $limit);
        $service = new UserService(0, $tutorsIDs);
        $service->setRoles(['tutor']);
        $service->setFetchAllAvailableUsers(false);

        $this->filteredTutors = $service->getUsersByPage($offset, $limit);
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