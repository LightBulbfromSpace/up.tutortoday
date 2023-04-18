<?php
/**
 * @var array $arResult
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<div class="container-custom">
    <?php if ($arResult['isErr']): ?>
        <article class="message is-danger">
            <div class="message-body">
                <?=$arResult['errText']?>
            </div>
        </article>
    <?php endif; ?>
    <form method="post" action="/registration/">
        <div class="field">
            <label class="label">Name</label>
            <div class="control">
                <input class="input-custom" type="text" placeholder="Text input" name="name" minlength="1" maxlength="100" required>
            </div>
        </div>
        <div class="field">
            <label class="label">Surname</label>
            <div class="control">
                <input class="input-custom" type="text" placeholder="Text input" name="surname" minlength="1" maxlength="100" required>
            </div>
        </div>
        <div class="field">
            <label class="label">Middle Name</label>
            <div class="control">
                <input class="input-custom" type="text" placeholder="Text input" name="middle_name" minlength="1" maxlength="100">
            </div>
        </div>

        <div class="field">
            <label class="label">Password</label>
            <div class="control">
                <input class="input-custom" type="password" placeholder="Password input" name="password1" minlength="8" maxlength="100" required>
            </div>
        </div>

        <div class="field">
            <p class="help">Type your password once more</p>
            <div class="control">
                <input class="input-custom" type="password" placeholder="Password input" name="password2" minlength="8" maxlength="100" required>
            </div>
        </div>

        <div class="field">
            <label class="label">Email</label>
            <div class="control">
                <input class="input-custom" type="email" placeholder="Email input" name="email" maxlength="255" required>
            </div>
        </div>

        <div class="field">
            <label class="label">Phone</label>
            <div class="control">
                <input class="input-custom" type="text" placeholder="Phone input" name="phone" maxlength="20" required>
            </div>
        </div>

        <div class="field">
            <label class="label">City</label>
            <div class="control">
                <input class="input-custom" type="text" placeholder="City input" name="city" maxlength="100">
            </div>
        </div>

        <div class="field">
            <label class="label">Profile description</label>
            <div class="control">
                <textarea class="textarea-custom" placeholder="Description" name="description"></textarea>
            </div>
        </div>

        <div class="field">
            <label class="label">Education format</label>
            <div class="control">
                <div class="select">
                    <select name="education_format">
                        <?php foreach ($arResult['edFormats'] as $edFormat): ?>
                            <option value="<?=$edFormat->getID()?>"><?=$edFormat->getName()?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="field">
            <label class="label">Subject</label>
            <div class="control">
                <div class="select">
                    <select name="subject">
                        <option selected></option>
                        <?php foreach ($arResult['subjects'] as $subject): ?>
                            <option value="<?=$subject->getID()?>"><?=$subject->getName()?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <?=bitrix_sessid_post()?>

        <div class="field is-grouped is-justify-content-center">
            <div class="control">
                <button class="button is-dark">Register me</button>
            </div>
        </div>
    </form>
</div>