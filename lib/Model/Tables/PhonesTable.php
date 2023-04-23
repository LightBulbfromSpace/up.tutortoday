<?php

namespace Up\Tutortoday\Model\Tables;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator;

Loc::loadMessages(__FILE__);

/**
 * Class PhonesTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> USER_ID int mandatory
 * <li> PHONE_NUMBER string(20) optional
 * </ul>
 *
 * @package Bitrix\Tutortoday
 **/

class PhonesTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'up_tutortoday_phones';
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
            ))->configureTitle(Loc::getMessage('PHONES_ENTITY_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            (new IntegerField('USER_ID',
                []
            ))->configureTitle(Loc::getMessage('PHONES_ENTITY_USER_ID_FIELD'))
                ->configureRequired(true),
            (new StringField('PHONE_NUMBER',
                [
                    'validation' => [__CLASS__, 'validatePhoneNumber']
                ]
            ))->configureTitle(Loc::getMessage('PHONES_ENTITY_PHONE_NUMBER_FIELD')),
        ];
    }

    /**
     * Returns validators for PHONE_NUMBER field.
     *
     * @return array
     */
    public static function validatePhoneNumber()
    {
        return [
            new LengthValidator(null, 20),
        ];
    }
}