<?php

namespace Up\Tutortoday\Model\Tables;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

Loc::loadMessages(__FILE__);

/**
 * Class UserRoleTable
 *
 * Fields:
 * <ul>
 * <li> USER_ID int mandatory
 * <li> ROLE_ID int mandatory
 * </ul>
 *
 * @package Bitrix\Tutortoday
 **/

class UserRoleTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'up_tutortoday_user_role';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            (new IntegerField('USER_ID',
                []
            ))->configureTitle(Loc::getMessage('USER_ROLE_ENTITY_USER_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            (new Reference(
                'USER',
                \CUser::class,
                Join::on('this.USER_ID', 'ref.ID')
            )),
            (new IntegerField('ROLE_ID',
                []
            ))->configureTitle(Loc::getMessage('USER_ROLE_ENTITY_ROLE_ID_FIELD'))
                ->configurePrimary(true),
            (new Reference(
                'ROLE',
                RolesTable::class,
                Join::on('this.ROLE_ID', 'ref.ID')
            )),

        ];
    }
}