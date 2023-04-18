<?php
/**
 * @var CMain $APPLICATION
 */
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle(SITE_NAME);

$APPLICATION->IncludeComponent('up:tutortoday.main', '',  [
    'page' => getGetParam('page'),
]);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
