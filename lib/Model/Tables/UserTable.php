<?php
namespace Up\Tutortoday\Model\Tables;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\TextField,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

Loc::loadMessages(__FILE__);

/**
 * Class UserTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> NAME string(63) mandatory
 * <li> SURNAME string(63) mandatory
 * <li> MIDDLE_NAME string(63) optional
 * <li> ROLE_ID int mandatory
 * <li> SUBJECT_ID int optional
 * </ul>
 *
 * @package Bitrix\Tutortoday
 **/

class UserTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'up_tutortoday_user';
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
            ))->configureTitle(Loc::getMessage('USER_ENTITY_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            (new StringField('PASSWORD',
                [
                    'validation' => [__CLASS__, 'validatePassword']
                ]
            ))->configureTitle(Loc::getMessage('USER_ENTITY_PASSWORD_FIELD'))
                ->configureRequired(true),
            (new StringField('NAME',
                [
                    'validation' => [__CLASS__, 'validateName']
                ]
            ))->configureTitle(Loc::getMessage('USER_ENTITY_NAME_FIELD'))
                ->configureRequired(true),
            (new StringField('SURNAME',
                [
                    'validation' => [__CLASS__, 'validateSurname']
                ]
            ))->configureTitle(Loc::getMessage('USER_ENTITY_SURNAME_FIELD'))
                ->configureRequired(true),
            (new StringField('MIDDLE_NAME',
                [
                    'validation' => [__CLASS__, 'validateMiddleName']
                ]
            ))->configureTitle(Loc::getMessage('USER_ENTITY_MIDDLE_NAME_FIELD')),
            (new TextField('DESCRIPTION',
                []
            ))->configureTitle(Loc::getMessage('USER_ENTITY_DESCRIPTION_FIELD')),
            (new IntegerField('EDUCATION_FORMAT_ID',
                []
            ))->configureTitle(Loc::getMessage('USER_ENTITY_EDUCATION_FORMAT_ID_FIELD'))
                ->configureRequired(true),
            (new Reference(
                'EDUCATION_FORMAT',
                EducationFormatTable::class,
                Join::on('this.EDUCATION_FORMAT_ID', 'ref.ID')
            )),
            (new IntegerField('ROLE_ID',
                []
            ))->configureTitle(Loc::getMessage('USER_ENTITY_ROLE_ID_FIELD'))
                ->configureRequired(true),
            (new Reference(
                'ROLE',
                RolesTable::class,
                Join::on('this.ROLE_ID', 'ref.ID')
            )),
            (new IntegerField('SUBJECT_ID',
                []
            ))->configureTitle(Loc::getMessage('USER_ENTITY_SUBJECT_ID_FIELD')),
            (new Reference(
                'SUBJECT',
                SubjectTable::class,
                Join::on('this.SUBJECT_ID', 'ref.ID')
            )),
        ];
    }

    /**
     * Returns validators for PASSWORD field.
     *
     * @return array
     */
    public static function validatePassword()
    {
        return [
            new LengthValidator(null, 100),
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

    /**
     * Returns validators for SURNAME field.
     *
     * @return array
     */
    public static function validateSurname()
    {
        return [
            new LengthValidator(null, 100),
        ];
    }

    /**
     * Returns validators for MIDDLE_NAME field.
     *
     * @return array
     */
    public static function validateMiddleName()
    {
        return [
            new LengthValidator(null, 100),
        ];
    }
}
