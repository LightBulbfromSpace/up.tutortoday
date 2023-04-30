<?php

namespace Up\Tutortoday\Model\Tables;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

Loc::loadMessages(__FILE__);

/**
 * Class TutorSubjectTable
 *
 * Fields:
 * <ul>
 * <li> TUTOR_ID int mandatory
 * <li> SUBJECT_ID int mandatory
 * </ul>
 *
 * @package Bitrix\Tutortoday
 **/

class UserSubjectTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'up_tutortoday_user_subject';
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
            ))->configureTitle(Loc::getMessage('TUTOR_SUBJECT_ENTITY_TUTOR_ID_FIELD'))
                ->configurePrimary(true),
            (new Reference(
                'USER',
                \CUser::class,
                Join::on('this.USER_ID', 'ref.ID')
            )),
            (new IntegerField('SUBJECT_ID',
                []
            ))->configureTitle(Loc::getMessage('TUTOR_SUBJECT_ENTITY_SUBJECT_ID_FIELD'))
                ->configurePrimary(true),
            (new Reference(
                'SUBJECT',
                SubjectTable::class,
                Join::on('this.SUBJECT_ID', 'ref.ID')
            )),
            (new IntegerField('PRICE',
                []
            ))->configureTitle(Loc::getMessage('USER_SUBJECT_ENTITY_PRICE_FIELD')),
        ];
    }
}