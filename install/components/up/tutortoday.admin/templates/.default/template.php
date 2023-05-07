<?php
/**
 * @var array $arResult
 */

\Bitrix\Main\UI\Extension::load('up.adminpanel');

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<script src="/local/components/up/tutortoday.admin/templates/.default/scripts.js"></script>
<div class="container-custom">
    <div id="error-message-area"></div>
    <div class="menu-button-container" id="menu-button-container">
        <button id="admin-users-button" class="small-container menu-button menu-button-active">Users</button>
        <button id="admin-subjects-button" class="small-container menu-button">Subjects</button>
        <button id="admin-ed-formats-button" class="small-container menu-button">Education formats</button>
        <button id="admin-cities-button" class="small-container menu-button">Cities</button>
    </div>
    <div class="data-container">
        <div class="button-container">
            <button id="previous-button" class="pagination-button hidden">&lt;</button>
        </div>
        <div id="admin-data-area"></div>
        <div class="button-container">
            <button id="next-button" class="pagination-button">&gt;</button>
        </div>
    </div>
    <div id="admin-add-button-area"></div>
</div>
<script>
    BX.ready(() => {
        hideWarning()
        window.TutortodayAdminPanel = new BX.Up.Tutortoday.AdminPanel({
            errorMsgAreaID: 'error-message-area',
            dataAreaID: 'admin-data-area',
            addButtonAreaID: 'admin-add-button-area',
            buttonsContainerID: 'menu-button-container',
            userButtonID: 'admin-users-button',
            subjectsButtonID: 'admin-subjects-button',
            edFormatsButtonID: 'admin-ed-formats-button',
            citiesButtonID: 'admin-cities-button',
            previousButtonID: 'previous-button',
            nextButtonID: 'next-button',
        })
    })
</script>