<?php
/**
 * @var array $arResult
 */

use Bitrix\Main\Application;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$uri = Application::getInstance()->getContext()->getServer()->get('REQUEST_URI');

$uri = explode('?', $uri);

if ($uri[0] === '/' || $uri[0] === '/about/')
{
    include 'informational/header.php';
}
else
{
    include 'other/header.php';
}