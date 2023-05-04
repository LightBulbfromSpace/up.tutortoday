<?php
/**
 * @var array $arResult
 */

\Bitrix\Main\UI\Extension::load('up.adminpanel');

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<script src="/local/components/up/tutortoday.admin/templates/.default/scripts.js"></script>
<div class="container-custom">
    <div class="menu-button-container" id="menu-button-container">
        <button id="admin-users-button" class="small-container menu-button menu-button-active">Users</button>
        <button id="admin-subjects-button" class="small-container menu-button">Subjects</button>
        <button id="admin-ed-formats-button" class="small-container menu-button">Education formats</button>
        <button id="admin-cities-button" class="small-container menu-button">Cities</button>
    </div>
    <div id="admin-data-area"></div>
</div>
<script>
    BX.ready(() => {
        hideWarning()
        window.TutortodayAdminPanel = new BX.Up.Tutortoday.AdminPanel({
            dataAreaID: 'admin-data-area',
            buttonsContainerID: 'menu-button-container',
            userButtonID: 'admin-users-button',
            subjectsButtonID: 'admin-subjects-button',
            edFormatsButtonID: 'admin-ed-formats-button',
            citiesButtonID: 'admin-cities-button',
        })
    })
</script>