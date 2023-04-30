<?php
/**
 * @var CMain $APPLICATION
 */
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle(SITE_NAME);

$APPLICATION->IncludeComponent('up:tutortoday.profile-settings', '',  [
    'ID' => (int)request()->get('id'),
]);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
