<?php
/**
 * @var array $arResult
 */

\Bitrix\Main\UI\Extension::load('main.core');

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div class="container-custom">
    <div class="container-column-custom">
        <?php if ($arResult['isErr']): ?>
            <article class="message is-danger">
                <div class="message-body">
                    <?=$arResult['errText']?>
                </div>
            </article>
        <?php endif; ?>
        <form action="/login/" method="post">
            <div class="field">
                <p class="control">
                    <input class="input-custom" type="text" placeholder="Login" name="login" required>
                </p>
            </div>
            <div class="field">
                <p class="control">
                    <input class="input-custom" type="password" placeholder="Password" name="password" minlength="8" required>
                </p>
            </div>
            <?=bitrix_sessid_post()?>
            <div class="button-container">
                <p class="control">
                    <button class="button is-dark small-container" type="submit">
                        Login
                    </button>
                    <a class="button is-dark small-container" href="/registration/">
                        Sign up
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
<script>
    BX.ready(() => {
        let elems = document.getElementsByClassName('tablebodytext')
        if (elems[0]) {
            elems[0].remove()
        }
    })
</script>