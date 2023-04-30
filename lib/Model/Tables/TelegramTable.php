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
 * Class TelegramTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> USER_ID int mandatory
 * <li> TELEGRAM_USERNAME string(32) optional
 * </ul>
 *
 * @package Bitrix\Tutortoday
 **/

class TelegramTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'up_tutortoday_telegram';
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
            ))->configureTitle(Loc::getMessage('TELEGRAM_ENTITY_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            (new IntegerField('USER_ID',
                []
            ))->configureTitle(Loc::getMessage('TELEGRAM_ENTITY_USER_ID_FIELD'))
                ->configureRequired(true),
            (new Reference(
                'USER',
                \CUser::class,
                Join::on('this.USER_ID', 'ref.ID')
            )),
            (new StringField('TELEGRAM_USERNAME',
                [
                    'validation' => [__CLASS__, 'validateTelegramUsername']
                ]
            ))->configureTitle(Loc::getMessage('TELEGRAM_ENTITY_TELEGRAM_USERNAME_FIELD')),
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