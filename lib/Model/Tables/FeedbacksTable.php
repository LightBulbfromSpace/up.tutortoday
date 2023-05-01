<?php
namespace Up\Tutortoday\Model\Tables;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\TextField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\UserTable;

Loc::loadMessages(__FILE__);

/**
 * Class FeedbacksTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> TUTOR_ID int mandatory
 * <li> TITLE string(100) mandatory
 * <li> DESCRIPTION string(500) optional
 * <li> STARS_NUMBER int optional
 * </ul>
 *
 * @package Bitrix\Tutortoday
 **/

class FeedbacksTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'up_tutortoday_feedbacks';
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
            ))->configureTitle(Loc::getMessage('FEEDBACKS_ENTITY_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            (new IntegerField('TUTOR_ID',
                []
            ))->configureTitle(Loc::getMessage('FEEDBACKS_ENTITY_TUTOR_ID_FIELD'))
                ->configureRequired(true),
            (new Reference(
                'TUTOR',
                UserTable::class,
                Join::on('this.TUTOR_ID', 'ref.ID')
            )),
            (new IntegerField('STUDENT_ID',
                []
            ))->configureTitle(Loc::getMessage('FEEDBACKS_ENTITY_STUDENT_ID_FIELD'))
                ->configureRequired(true),
            (new Reference(
                'STUDENT',
                UserTable::class,
                Join::on('this.STUDENT_ID', 'ref.ID')
            )),
            (new StringField('TITLE',
                [
                    'validation' => [__CLASS__, 'validateTitle']
                ]
            ))->configureTitle(Loc::getMessage('FEEDBACKS_ENTITY_TITLE_FIELD'))
                ->configureRequired(true),
            (new TextField('DESCRIPTION',
                []
            ))->configureTitle(Loc::getMessage('FEEDBACKS_ENTITY_DESCRIPTION_FIELD')),
            (new IntegerField('STARS_NUMBER',
                []
            ))->configureTitle(Loc::getMessage('FEEDBACKS_ENTITY_STARS_NUMBER_FIELD')),
        ];
    }

    /**
     * Returns validators for TITLE field.
     *
     * @return array
     */
    public static function validateTitle()
    {
        return [
            new LengthValidator(null, 100),
        ];
    }
}