<?php
/**
 * @var array $arResult
 */

\Bitrix\Main\UI\Extension::load('main.core');

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<script type="text/javascript" src="/local/components/up/tutortoday.profile-settings/templates/.default/scripts.js"></script>
<div id="delete-form-area"></div>
<div id="add-photo-form-area"></div>
<form class="main-form" method="post" action="/profile/<?=$arResult['user']['mainData']['ID']?>/settings/">
    <?=bitrix_sessid_post()?>
    <div class="container-custom">
        <div class="container-narrow-custom">
            <div class="box">
                <div class="photo-container">
                    <img src="<?=$arResult['user']['photo']?>" id="profilePhoto" class="img-rounded img-fixed-size profile-photo" alt="avatar">
                    <button type="button" class="photo-button" onclick="openAddPhotoForm()">Open</button>
                </div>
                <button type="button" class="button-plus-minus button-large-custom container-margin-top" onclick="deleteProfilePhoto()">Delete</button>
                <div class="br"></div>
                <div class="label">Full name</div>
                <div class="box-dark-element-custom">
                    <input class="input-custom" name="name" placeholder="No name" value="<?=htmlspecialchars($arResult['user']['mainData']['NAME'])?>">
                </div>
                <div class="box-dark-element-custom">
                    <input class="input-custom" name="lastName" placeholder="No surname" value="<?=htmlspecialchars($arResult['user']['mainData']['LAST_NAME'])?>">
                </div>
                <div class="box-dark-element-custom">
                    <input class="input-custom" name="middleName" placeholder="No middle name" value="<?=htmlspecialchars($arResult['user']['mainData']['SECOND_NAME'])?>">
                </div>
                <div class="box-invisible-custom role-container-custom"><div>I'm a</div>&nbsp;<div class="box-small-dark-custom"><?=$arResult['user']['role']['NAME']?></div></div>
                <div class="br"></div>
                    <div id="msg-container"></div>
                    <label class="label">Password change</label>
                    <div class="box-dark-element-custom">
                        <input type="password" class="input-custom" placeholder="Old password" id="oldPassword" autocomplete="off">
                    </div>
                    <div class="box-dark-element-custom">
                        <input type="password" class="input-custom" placeholder="Password" id="newPassword" autocomplete="off">
                    </div>
                    <div class="box-dark-element-custom">
                        <input type="password" class="input-custom" placeholder="Confirm password" id="passwordConfirm" autocomplete="off">
                    </div>
                    <button type="button" class="button-plus-minus button-large-custom" onclick="updatePassword()">Save</button>
                <div class="br"></div>
                <div class="container-contacts">
                    <label class="label">Email</label>
                    <div class="box-dark-element-custom">
                        <input class="input-custom" name="workingEmail" value="<?=$arResult['user']['contacts']['email']?>">
                    </div>
                </div>
                <div class="container-contacts">
                    <label class="label">Phone</label>
                    <div class="box-dark-element-custom">
                        <input class="input-custom" name="phoneNumber" value="<?=htmlspecialchars($arResult['user']['contacts']['phone'])?>">
                    </div>
                </div>
                <div class="container-contacts">
                    <label class="label">VK</label>
                    <?php foreach ($arResult['user']['contacts']['vk'] as $contact): ?>
                        <div class="container-row-custom items-aligned-center">
                            <div class="box-dark-element-custom">
                                <input class="input-custom" value="<?=htmlspecialchars($contact['VK_PROFILE'])?>">
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <button type="button" class="button-plus-minus button-large-custom">+</button>
                </div>
                <div class="container-contacts">
                    <label class="label">Telegram</label>
                    <?php foreach ($arResult['user']['contacts']['telegram'] as $contact): ?>
                        <div class="container-row-custom items-aligned-center">
                            <div class="box-dark-element-custom">
                                <input class="input-custom" value="<?=htmlspecialchars($contact['TELEGRAM_USERNAME'])?>">
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <button type="button" class="button-plus-minus button-large-custom">+</button>
                </div>
            </div>
        </div>

        <div class="container-large-custom">
            <div class="save-button-container-main">
                <div class="save-button-container">
                    <a class="link-button" href="/profile/<?=$arResult['user']['mainData']['ID']?>/">Back</a>
                </div>
                <div class="save-button-container">
                    <button type="button" class="button-plus-minus button-small-custom container-margin-top-bottom" onclick="openDeleteProfileForm(<?=$arResult['user']['mainData']['ID']?>)">Delete Profile</button>
                    <button type="submit" class="button-plus-minus button-small-custom container-margin-top-bottom" onclick="submitForms()">Save Changes</button>
                </div>
            </div>
            <label class="label">Description</label>
            <div class="box-dark-element-custom">
                <textarea class="textarea-custom" name="description" placeholder="No description"><?=htmlspecialchars($arResult['user']['description'])?></textarea>
            </div>
            <div class="container-row-custom">
                <div class="container-column-custom">
                    <div class="container-column-custom">
                        <label class="label">Education format</label>
                        <div class="control">
                            <div class="box">
                                <?php foreach ($arResult['edFormats'] as $edFormat): ?>
                                    <div class="form-check-custom">
                                        <input class="form-check-input" name="edFormats[]"
                                               type="checkbox" value="<?=$edFormat['ID']?>"
                                               <?php if ($arResult['user']['edFormatsIDs'] !== null): ?>
                                               <?=in_array($edFormat['ID'], $arResult['user']['edFormatsIDs']) ? 'checked' : ''?>
                                               <?php endif; ?>>
                                        <label class="form-check-label" for="<?= $edFormat['NAME']?>">
                                            <?= $edFormat['NAME']?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <label class="label">City</label>
                    <div class="field">
                        <div class="select-custom">
                            <select name="city">
                                <option value=""></option>
                                <?php foreach ($arResult['cities'] as $city): ?>
                                    <option value="<?=$city['ID']?>" <?=$arResult['user']['city'] === $city['NAME'] ? 'selected' : ''?>><?=$city['NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="container-column-custom">
                        <label class="label">Subjects</label>
                        <div class="box">
                            <?php if($arResult['user']['subjects'] != null): ?>
                                <?php foreach ($arResult['user']['subjects'] as $subject): ?>
                                    <div class="container-subjects" id="subject-container-<?=$subject['SUBJECT']['ID']?>">
                                        <div class="container-subjects">
                                            <div class="box-dark-element-custom">
                                                <?=$subject['SUBJECT']['NAME']?>
                                            </div>
                                                <div class="container-row-custom is-aligned-center">
                                                    <input type="number" class="input-custom" name="subjectsPrices[<?=$subject['SUBJECT']['ID']?>]" value="<?=$subject['PRICE']?>">
                                                    <div class="price">rub/hour</div>
                                                </div>
                                        </div>
                                        <button type="button" class="button-plus-minus button-large-custom" onclick="deleteSubject(<?=$subject['SUBJECT']['ID']?>, <?=$arResult['user']['mainData']['ID']?>)">-</button>
                                    </div>
                                <?php endforeach; ?>
                                <div class="br"></div>
                            <?php endif; ?>
                            <div class="container-subjects">
                                <button type="button" class="button-plus-minus button-large-custom" onclick="AddSubjectForm()">+</button>
                            </div>
                        </div>
                    </div>
                    <div id="add-subject-area"></div>
                    <div class="box">
                        <button type="button" class="button-plus-minus button-large-custom" onclick="closeSubjectForm()">-</button>
                    </div>
                    <div class="container-contacts">
                </div>
                </div>
                <div class="container-column-custom">
                    <label class="label">Days of week</label>
                    <div class="box-stretched-custom">
                        <?php foreach ($arResult['weekdays'] as $weekday): ?>
                            <button type="button" class="box-button" id="weekday-<?=$weekday['ID']?>" onclick="getTime(<?=$arResult['user']['mainData']['ID']?>, <?=$weekday['ID']?>)"><?=$weekday['NAME']?></button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="container-column-custom">
                    <label class="label">Available time</label>
                    <div class="container-time-custom">
                        <div class="box-time-custom is-aligned-center" id="free-time-area">
                            <div>Select the weekday</div>
                        </div>
                        <div class="box-time-custom is-aligned-center">
                            <button type="button" class="button-plus-minus button-large-custom" id="add-time-button" onclick="showTimepicker()">+</button>
                            <div id="timepicker-form">
                                <form class="timepicker">
                                    <div class="formfield">
                                        <label>From:</label>
                                        <input type="time" id="time-from" name="time-from" min="00:00" max="23:59"/>
                                    </div>

                                    <div class="formfield">
                                        <label>To:</label>
                                        <input type="time" id="time-to" name="time-to" min="00:00" max="23:59"/>
                                    </div>
                                </form>
                                <button type="button" class="button-plus-minus button-small-custom" onclick="addTime(<?=$arResult['user']['mainData']['ID']?>); closeTimepicker()">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    BX.ready(() => {
        let elems = document.getElementsByClassName('tablebodytext')
        if (elems[0]) {
            elems[0].remove()
        }
        let input = document.getElementById('file-input')
        input.addEventListener('input', () => {
            console.log('here')
            updatePhoto()
        })
    })
</script>