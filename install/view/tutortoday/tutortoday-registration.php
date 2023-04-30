<?php
/**
 * @var CMain $APPLICATION
 */
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle(SITE_NAME);

$APPLICATION->IncludeComponent('up:tutortoday.registration', '',  [
    'err' => getGetParam('err'),
]);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
