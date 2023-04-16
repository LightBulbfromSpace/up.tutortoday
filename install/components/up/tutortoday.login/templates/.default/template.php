<?php
/**
 * @var array $arResult
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div class="container-custom">
    <?php if ($arResult['err'] == 'auth'): ?>
        <article class="message is-danger">
            <div class="message-body">
                Invalid login or password
            </div>
        </article>
    <?php endif; ?>
    <?php if ($arResult['err'] == 'empty'): ?>
        <article class="message is-danger">
            <div class="message-body">
                Login and password can't be empty
            </div>
        </article>
    <?php endif; ?>
    <form action="/login/" method="post">
        <div class="field">
            <p class="control">
                <input class="input" type="email" placeholder="Email" name="email" required>
            </p>
        </div>
        <div class="field">
            <p class="control">
                <input class="input" type="password" placeholder="Password" name="password" minlength="8" required>
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
