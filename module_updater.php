<?php

use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;

function __tutortodayMigrate(int $nextVersion, callable $callback)
{
    global $DB;
    $moduleId = 'up.TutorToday';

    if (!ModuleManager::isModuleInstalled($moduleId))
    {
        return;
    }

    $currentVersion = intval(Option::get($moduleId, '~database_schema_version', 0));

    if ($currentVersion < $nextVersion)
    {
        include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/update_class.php');
        $updater = new \CUpdater();
        $updater->Init('', 'mysql', '', '', $moduleId, 'DB');

        $callback($updater, $DB, 'mysql');
        Option::set($moduleId, '~database_schema_version', $nextVersion);
    }
}

__tutortodayMigrate(2, function($updater, $DB)
{
    if ($updater->CanUpdateDatabase())
    {
        $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/local/modules/up.tutortoday/install/db/install_data.sql');
    }
});