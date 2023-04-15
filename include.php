<?php

use Bitrix\Main\Application;
use Bitrix\Main\DB\Connection;
use Bitrix\Main\Request;
use Bitrix\Main\Type\ParameterDictionary;

const SITE_NAME = 'TutorToday';
const MODULE_ROOT = __DIR__;

function request(): Request
{
    return Application::getInstance()->getContext()->getRequest();
}

function getPostList() : ParameterDictionary
{
    return \Bitrix\Main\Context::getCurrent()->getRequest()->getPostList();
}

function db(): Connection
{
    return Application::getConnection();
}

if (file_exists(MODULE_ROOT . '/module_updater.php'))
{
    include (MODULE_ROOT . '/module_updater.php');
}
