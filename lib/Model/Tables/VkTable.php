<?php

namespace Up\Tutortoday\Model\Tables;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator;

Loc::loadMessages(__FILE__);

/**
 * Class VkTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> USER_ID int mandatory
 * <li> VK_PROFILE string(100) optional
 * </ul>
 *
 * @package Bitrix\Tutortoday
 **/

class VkTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'up_tutortoday_vk';
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
            ))->configureTitle(Loc::getMessage('VK_ENTITY_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            (new IntegerField('USER_ID',
                []
            ))->configureTitle(Loc::getMessage('VK_ENTITY_USER_ID_FIELD'))
                ->configureRequired(true),
            (new StringField('VK_PROFILE',
                [
                    'validation' => [__CLASS__, 'validateVkProfile']
                ]
            ))->configureTitle(Loc::getMessage('VK_ENTITY_VK_PROFILE_FIELD')),
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
}