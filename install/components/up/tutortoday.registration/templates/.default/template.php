<?php
/**
 * @var array $arResult
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<script src="/local/components/up/tutortoday.registration/templates/.default/scripts.js"></script>
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
                <input class="input-custom" type="text" placeholder="Text input" name="lastName" minlength="1" maxlength="100" required>
            </div>
        </div>
        <div class="field">
            <label class="label">Middle Name</label>
            <div class="control">
                <input class="input-custom" type="text" placeholder="Text input" name="middleName" minlength="1" maxlength="100">
            </div>
        </div>

        <div class="field">
            <label class="label">Login</label>
            <div class="control">
                <input class="input-custom" type="text" placeholder="Login input" name="login" minlength="8" maxlength="100" required>
            </div>
        </div>

        <div class="field">
            <label class="label">Password</label>
            <div class="control">
                <input class="input-custom" type="password" placeholder="Password input" name="password" minlength="8" maxlength="100" required>
            </div>
        </div>

        <div class="field">
            <p class="help">Type your password once more</p>
            <div class="control">
                <input class="input-custom" type="password" placeholder="Password input" name="confirmPassword" minlength="8" maxlength="100" required>
            </div>
        </div>

        <div class="field">
            <label class="label">Email</label>
            <div class="control">
                <input class="input-custom" type="email" placeholder="Email input" name="email" maxlength="255" required>
            </div>
        </div>

        <div class="field">
            <label class="label">Working Email</label>
            <div class="control">
                <input class="input-custom" type="email" placeholder="Email input" name="workingEmail" maxlength="255" required>
            </div>
            <p class="help">This email will be visible on your page</p>
        </div>

        <div class="field">
            <label class="label">Working Phone</label>
            <div class="control">
                <input class="input-custom" type="tel" placeholder="Phone input" name="phoneNumber" maxlength="20" required>
            </div>
        </div>

        <label class="label">City</label>
            <div class="field">
            <div class="select-custom">
                <select name="city">
                    <option></option>
                    <?php foreach ($arResult['cities'] as $city): ?>
                        <option value="<?=$city['ID']?>"><?=$city['NAME']?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="field is-grouped">
            <label class="label"></label>
            <div class="control">
                <button class="button is-dark" type="button" id="select-subjects-button" onclick="showSubjects()">Select subjects</button>
            </div>
            <label class="label"></label>
            <div class="control">
                <button class="button is-dark" type="button" id="select-ed-formats-button" onclick="showEdFormats()">Select educational formats</button>
            </div>
        </div>

        <div class="popup-form-custom" id="popup-form-subjects-custom">
            <div class="box">
                <div class="field">
                    <label class="is-justified-center bold">Subject</label>
                    <div class="checkbox-container-custom">
                        <?php foreach ($arResult['subjects'] as $subject): ?>
                        <label class="checkbox checkbox-elem-custom">
                            <input type="checkbox" class="field checkbox-custom" value="<?=$subject->getID()?>" name="subjects[]">
                            <?=$subject->getName()?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="field is-justified-center">
                    <label class="label"></label>
                    <div class="control">
                        <button class="button is-dark" type="button" id="select-subjects-save-button" onclick="closeSubjects()">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="popup-form-custom popup-small-form-custom" id="popup-form-ed-formats-custom">
            <div class="box">
                <div class="field">
                    <label class="is-justified-center bold">Education Format</label>
                    <div class="checkbox-container-custom">
                        <?php foreach ($arResult['edFormats'] as $edFormat): ?>
                            <label class="checkbox checkbox-elem-custom">
                                <input type="checkbox" class="field checkbox-custom" value="<?=$edFormat->getID()?>" name="edFormats[]">
                                <?=$edFormat->getName()?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="is-justified-center">
                    <label class="label"></label>
                    <div class="control">
                        <button class="button is-dark" type="button" id="select-ed-formats-save-button" onclick="closeEdFormats()">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <label class="label">I'm a</label>
        <div class="field">
            <div class="select-custom">
                <select name="role">
                    <?php foreach ($arResult['roles'] as $role): ?>
                        <?php if ($role['NAME'] === 'administrator') continue; ?>
                        <option value="<?=$role['ID']?>"><?=$role['NAME']?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <p class="help">Attention! Role cannot be changed in future.</p>
        </div>

        <div class="field">
            <label class="label">Profile description</label>
            <div class="control">
                <textarea class="textarea-custom" placeholder="Description" name="description"></textarea>
            </div>
        </div>

        <?=bitrix_sessid_post()?>

        <div class="field is-grouped is-justify-content-center">
            <div class="control">
                <button class="button is-dark" id="confirm-registration-button">Register me</button>
            </div>
        </div>
    </form>
</div>
<script>
    BX.ready(() => {
        let elems = document.getElementsByClassName('tablebodytext')
        if (elems[0]) {
            elems[0].remove()
        }
    })
</script>