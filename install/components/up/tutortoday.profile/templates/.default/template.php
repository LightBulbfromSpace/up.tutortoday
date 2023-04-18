<?php
/**
 * @var array $arResult
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div class="container-main-custom">
    <div class="container-custom">
        <form action="/" method="post">
            filters
            <?=bitrix_sessid_post()?>
        </form>
    </div>
    <div class="container-custom">
        <form action="/" method="get">
            search
            <?=bitrix_sessid_post()?>
        </form>
        <div class="container-content-custom"></div>
    </div>
</div>