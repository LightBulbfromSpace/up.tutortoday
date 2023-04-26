<?php

namespace Up\Tutortoday\Services;

use Bitrix\Main\Entity\Query;
use Up\Tutortoday\Model\Tables\SubjectTable;

class FiltersService
{
	public static function getTutorsByFilters($arrFilters)
	{
		$tutors = userTable::query()
			->whereBetween('PRICE', $arrFilters['PRICE_MIN'], $arrFilters['PRICE_MAX'])
			->whereIn("EducationFormatTable" . "NAME", $arrFilters['FORMATS'])
			->whereIn("SubjectTable" . "NAME", $arrFilters['SUBJECTS']);


		return $tutors->fetchCollection();
	}

	public static function getTutorsByName($name)
	{
		$tutors = UserTable::query()->setSelect(['*'])
			->where(Query::expr()->concat("SURNAME", "NAME", "MIDDLE_NAME"), 'like', $name);

		return $tutors->fetchCollection();
	}
}