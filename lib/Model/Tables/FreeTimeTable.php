<?php

namespace Up\Tutortoday\Model\Tables;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\DatetimeField,
    Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

Loc::loadMessages(__FILE__);

/**
 * Class FreeTimeTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> USER_ID int mandatory
 * <li> START datetime mandatory
 * <li> END datetime mandatory
 * </ul>
 *
 * @package Bitrix\Tutortoday
 **/

class FreeTimeTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'up_tutortoday_free_time';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            (new IntegerField('ID',
                []
            ))->configureTitle(Loc::getMessage('FREE_TIME_ENTITY_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            (new IntegerField('USER_ID',
                []
            ))->configureTitle(Loc::getMessage('FREE_TIME_ENTITY_USER_ID_FIELD'))
                ->configureRequired(true),
            (new Reference(
                'USER',
                UserTable::class,
                Join::on('this.USER_ID', 'ref.ID')
            )),
            (new IntegerField('WEEKDAY_ID',
                []
            ))->configureTitle(Loc::getMessage('FREE_TIME_ENTITY_WEEKDAY_ID_FIELD'))
                ->configureRequired(true),
            (new Reference(
                'WEEKDAY',
                WeekdaysTable::class,
                Join::on('this.WEEKDAY_ID', 'ref.ID')
            )),
            (new DatetimeField('START',
                []
            ))->configureTitle(Loc::getMessage('FREE_TIME_ENTITY_START_FIELD'))
                ->configureRequired(true),
            (new DatetimeField('END',
                []
            ))->configureTitle(Loc::getMessage('FREE_TIME_ENTITY_END_FIELD'))
                ->configureRequired(true),
        ];
    }
}