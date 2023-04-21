<?php

namespace Up\Tutortoday\Services;

use Up\Tutortoday\Model\Tables\UserTable;

class FiltersService
{
	public static function getTutorsByFilters()
	{

		$users = userTable::query()->setSelect(['*'])
			->where('', )
			->setOrder(['ID' => 'DESC'])
			->setOffset($offset)
			->setLimit(USERS_BY_PAGE);

		return $users->fetchCollection();
	}
}