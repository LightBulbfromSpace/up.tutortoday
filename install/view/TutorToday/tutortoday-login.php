<?php
/**
 * @var CMain $APPLICATION
 */
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle(SITE_NAME);

$APPLICATION->IncludeComponent('up:TutorToday.login', '', []);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
