<?php

namespace Up\Tutortoday\Model\Tables;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\UserTable;

Loc::loadMessages(__FILE__);

/**
 * Class UserDescriptionTable
 *
 * Fields:
 * <ul>
 * <li> USER_ID int mandatory
 * <li> DESCRIPTION text optional
 * </ul>
 *
 * @package Bitrix\Tutortoday
 **/

class UserDescriptionTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'up_tutortoday_user_description';
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
            ))->configureTitle(Loc::getMessage('USER_DESCRIPTION_ENTITY_USER_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            (new Reference(
                'USER',
                UserTable::class,
                Join::on('this.USER_ID', 'ref.ID')
            )),
            (new TextField('DESCRIPTION',
                []
            ))->configureTitle(Loc::getMessage('USER_DESCRIPTION_ENTITY_DESCRIPTION_FIELD')),
        ];
    }
}