<?php
namespace Bitrix\Tutortoday;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator;

Loc::loadMessages(__FILE__);

/**
 * Class EducationFormatTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> NAME string(100) mandatory
 * </ul>
 *
 * @package Bitrix\Tutortoday
 **/

class EducationFormatTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'up_tutortoday_education_format';
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
            ))->configureTitle(Loc::getMessage('EDUCATION_FORMAT_ENTITY_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            (new StringField('NAME',
                [
                    'validation' => [__CLASS__, 'validateName']
                ]
            ))->configureTitle(Loc::getMessage('EDUCATION_FORMAT_ENTITY_NAME_FIELD'))
                ->configureRequired(true),
        ];
    }

    /**
     * Returns validators for NAME field.
     *
     * @return array
     */
    public static function validateName()
    {
        return [
            new LengthValidator(null, 100),
        ];
    }
}