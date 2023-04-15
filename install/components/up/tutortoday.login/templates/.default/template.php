<?php
/**
 * @var array $arResult
 */

//\Bitrix\Main\UI\Extension::load('main.core');

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div class="container-custom">
    <form action="/login/" method="post">
        <div class="field">
            <p class="control">
                <input class="input" type="email" placeholder="Email">
            </p>
        </div>
        <div class="field">
            <p class="control">
                <input class="input" type="password" placeholder="Password">
            </p>
        </div>
        <?=bitrix_sessid_post()?>
        <div class="field is-grouped is-justify-content-center">
            <p class="control">
                <button class="button is-success" type="submit">
                    Login
                </button>
                <a class="button is-success" href="/registration/">
                    Sign up
                </a>
            </p>
        </div>
    </form>
</div>
