<?php
/**
 * @var array $arResult
 */

use Bitrix\Main\Application;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $USER;

if ($USER->IsAuthorized()) {
    $arFilter = ["ID" => $USER->GetID()];
    $dbUserList = CUser::GetList($by = "id", $order = "asc", $arFilter);
    if ($dbUserList->Fetch()['BLOCKED'] == 'Y') {
        $USER->Logout();
        LocalRedirect('/login/?err=blocked');
    }
}

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