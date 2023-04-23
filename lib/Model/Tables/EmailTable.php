<?php

namespace Up\Tutortoday\Model\Tables;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator;

Loc::loadMessages(__FILE__);

/**
 * Class EmailTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> USER_ID int mandatory
 * <li> EMAIL string(255) optional
 * </ul>
 *
 * @package Bitrix\Tutortoday
 **/

class EmailTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'up_tutortoday_email';
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
            ))->configureTitle(Loc::getMessage('EMAIL_ENTITY_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            (new IntegerField('USER_ID',
                []
            ))->configureTitle(Loc::getMessage('EMAIL_ENTITY_USER_ID_FIELD'))
                ->configureRequired(true),
            (new StringField('EMAIL',
                [
                    'validation' => [__CLASS__, 'validateEmail']
                ]
            ))->configureTitle(Loc::getMessage('EMAIL_ENTITY_EMAIL_FIELD')),
        ];
    }

    /**
     * Returns validators for EMAIL field.
     *
     * @return array
     */
    public static function validateEmail()
    {
        return [
            new LengthValidator(null, 255),
        ];
    }
}