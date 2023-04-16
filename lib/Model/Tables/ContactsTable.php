<?php
namespace Up\Tutortoday\Model\Tables;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

Loc::loadMessages(__FILE__);

/**
 * Class ContactsTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> TUTOR_ID int mandatory
 * <li> PHONE_NUMBER string(16) optional
 * <li> EMAIL string(320) optional
 * <li> VK_PROFILE string(100) optional
 * <li> TELEGRAM_USERNAME string(32) optional
 * </ul>
 *
 * @package Bitrix\Tutortoday
 **/

class ContactsTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'up_tutortoday_contacts';
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
            ))->configureTitle(Loc::getMessage('CONTACTS_ENTITY_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            (new IntegerField('USER_ID',
                []
            ))->configureTitle(Loc::getMessage('CONTACTS_ENTITY_USER_ID_FIELD'))
                ->configureRequired(true),
            (new Reference(
                'USER',
                UserTable::class,
                Join::on('this.USER_ID', 'ref.ID')
            )),
            (new StringField('PHONE_NUMBER',
                [
                    'validation' => [__CLASS__, 'validatePhoneNumber']
                ]
            ))->configureTitle(Loc::getMessage('CONTACTS_ENTITY_PHONE_NUMBER_FIELD')),
            (new StringField('EMAIL',
                [
                    'validation' => [__CLASS__, 'validateEmail']
                ]
            ))->configureTitle(Loc::getMessage('CONTACTS_ENTITY_EMAIL_FIELD')),
            (new StringField('VK_PROFILE',
                [
                    'validation' => [__CLASS__, 'validateVkProfile']
                ]
            ))->configureTitle(Loc::getMessage('CONTACTS_ENTITY_VK_PROFILE_FIELD')),
            (new StringField('TELEGRAM_USERNAME',
                [
                    'validation' => [__CLASS__, 'validateTelegramUsername']
                ]
            ))->configureTitle(Loc::getMessage('CONTACTS_ENTITY_TELEGRAM_USERNAME_FIELD')),
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

    /**
     * Returns validators for VK_PROFILE field.
     *
     * @return array
     */
    public static function validateVkProfile()
    {
        return [
            new LengthValidator(null, 100),
        ];
    }

    /**
     * Returns validators for TELEGRAM_USERNAME field.
     *
     * @return array
     */
    public static function validateTelegramUsername()
    {
        return [
            new LengthValidator(null, 32),
        ];
    }
}