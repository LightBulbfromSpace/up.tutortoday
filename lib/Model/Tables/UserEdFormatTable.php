<?php

namespace Up\Tutortoday\Model\Tables;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

Loc::loadMessages(__FILE__);

/**
 * Class UserEdFormatTable
 *
 * Fields:
 * <ul>
 * <li> USER_ID int mandatory
 * <li> EDUCATION_FORMAT_ID int mandatory
 * </ul>
 *
 * @package Bitrix\Tutortoday
 **/

class UserEdFormatTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'up_tutortoday_user_ed_format';
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
            ))->configureTitle(Loc::getMessage('USER_ED_FORMAT_ENTITY_USER_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            (new Reference(
                'USER',
                \CUser::class,
                Join::on('this.USER_ID', 'ref.ID')
            )),
            (new IntegerField('EDUCATION_FORMAT_ID',
                []
            ))->configureTitle(Loc::getMessage('USER_ED_FORMAT_ENTITY_EDUCATION_FORMAT_ID_FIELD'))
                ->configurePrimary(true),
            (new Reference(
                'EDUCATION_FORMAT',
                EducationFormatTable::class,
                Join::on('this.EDUCATION_FORMAT_ID', 'ref.ID')
            )),
        ];
    }
}