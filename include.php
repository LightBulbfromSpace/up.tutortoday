<?php

use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\DB\Connection;
use Bitrix\Main\Request;
use Bitrix\Main\Session\SessionInterface;
use Bitrix\Main\Type\ParameterDictionary;

const SITE_NAME = 'TutorToday';
const USERS_BY_PAGE = 3;
const MODULE_ROOT = __DIR__;

function request(): Request
{
    return Application::getInstance()->getContext()->getRequest();
}

function getGetParam(string $name)
{
    return Context::getCurrent()->getRequest()->getQuery($name);
}

function getPostList() : ParameterDictionary
{
    return Context::getCurrent()->getRequest()->getPostList();
}

function db(): Connection
{
    return Application::getConnection();
}

function session() : SessionInterface
{
    return Application::getInstance()->getSession();
}

if (file_exists(MODULE_ROOT . '/module_updater.php'))
{
    include (MODULE_ROOT . '/module_updater.php');
}
