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
 * Class ProfileImagesTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> USER_ID int mandatory
 * <li> LINK string(255) mandatory
 * <li> WIDTH int mandatory
 * <li> HEIGHT int mandatory
 * <li> EXTENSION string(10) mandatory
 * </ul>
 *
 * @package Bitrix\Tutortoday
 **/

class ProfileImagesTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'up_tutortoday_profile_images';
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
            ))->configureTitle(Loc::getMessage('PROFILE_IMAGES_ENTITY_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            (new IntegerField('USER_ID',
                []
            ))->configureTitle(Loc::getMessage('PROFILE_IMAGES_ENTITY_USER_ID_FIELD'))
                ->configureRequired(true),
            (new Reference(
                'USER',
                \CUser::class,
                Join::on('this.USER_ID', 'ref.ID')
            )),
            (new StringField('LINK',
                [
                    'validation' => [__CLASS__, 'validateLink']
                ]
            ))->configureTitle(Loc::getMessage('PROFILE_IMAGES_ENTITY_LINK_FIELD'))
                ->configureRequired(true),
            (new IntegerField('WIDTH',
                []
            ))->configureTitle(Loc::getMessage('PROFILE_IMAGES_ENTITY_WIDTH_FIELD'))
                ->configureRequired(true),
            (new IntegerField('HEIGHT',
                []
            ))->configureTitle(Loc::getMessage('PROFILE_IMAGES_ENTITY_HEIGHT_FIELD'))
                ->configureRequired(true),
            (new StringField('EXTENSION',
                [
                    'validation' => [__CLASS__, 'validateExtension']
                ]
            ))->configureTitle(Loc::getMessage('PROFILE_IMAGES_ENTITY_EXTENSION_FIELD'))
                ->configureRequired(true),
        ];
    }

    /**
     * Returns validators for LINK field.
     *
     * @return array
     */
    public static function validateLink()
    {
        return [
            new LengthValidator(null, 255),
        ];
    }

    /**
     * Returns validators for EXTENSION field.
     *
     * @return array
     */
    public static function validateExtension()
    {
        return [
            new LengthValidator(null, 10),
        ];
    }
}