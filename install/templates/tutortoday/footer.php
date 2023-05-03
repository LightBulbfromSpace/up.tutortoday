<?php
/**
 * @var array $arResult
 */

use Bitrix\Main\Application;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$uri = Application::getInstance()->getContext()->getServer()->get('REQUEST_URI');

if ($uri !== '/')
{
    include 'other/footer.php';
}
