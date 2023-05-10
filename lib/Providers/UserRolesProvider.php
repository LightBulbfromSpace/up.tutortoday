<?php

namespace Up\Tutortoday\Providers;

use Up\Tutortoday\Model\Tables\RolesTable;

class UserRolesProvider
{
    public static function getAllRoles()
    {
        return RolesTable::query()->setSelect(['*'])->fetchCollection();
    }

    public static function getRoleIDbyName(string $name = 'tutor') : int|null
    {
        $role = RolesTable::query()->where('NAME', $name);
        if ($role === null)
        {
            return null;
        }

        return $role->fetchObject()->getID();
    }

}