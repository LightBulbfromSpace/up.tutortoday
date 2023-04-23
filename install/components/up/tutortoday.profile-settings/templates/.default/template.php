<?php
/**
 * @var array $arResult
 */

// Photo
// Full name
// Contacts (email, phone, telegram, vk)
// Role

// Education format
// City
// Subjects
// Description

// Feedbacks (in public part)

\Bitrix\Main\UI\Extension::load('main.core');

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<script type="text/javascript" src="/local/components/up/tutortoday.profile-settings/templates/.default/scripts.js"></script>
<div class="container-custom">
    <div class="container-narrow-custom">
        <div class="box">
            <img src="<?=$arResult['user']['photo']?>" class="img-rounded" alt="avatar">
            <div class="box-dark-element-custom">
                <input class="input-custom" placeholder="No name" value="<?=$arResult['user']['mainData']['NAME']?>">
            </div>
            <div class="box-dark-element-custom">
                <input class="input-custom" placeholder="No surname" value="<?=$arResult['user']['mainData']['SURNAME']?>">
            </div>
            <div class="box-dark-element-custom">
                <input class="input-custom" placeholder="No middle name" value="<?=$arResult['user']['mainData']['MIDDLE_NAME']?>">
            </div>
            <div class="box-invisible-custom">I'm a <?=$arResult['user']['mainData']['ROLE']['NAME']?></div>
            <div class="br"></div>
            <div class="container-contacts">
                <label class="label">Email</label>
                <?php foreach ($arResult['user']['contacts']['email'] as $i => $contact): ?>
                    <div class="container-row-custom items-aligned-center">
                        <div class="box-dark-element-custom">
                            <input class="input-custom" value="<?=$contact['EMAIL']?>">
                        </div>
                        <?php if ($i !== 0 ): ?>
                            <button class="button-plus-minus button-small-custom">-</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <button class="button-plus-minus button-large-custom">+</button>
            </div>
            <div class="container-contacts">
                <label class="label">Phone</label>
                <?php foreach ($arResult['user']['contacts']['phone'] as $i => $contact): ?>
                    <div class="container-row-custom items-aligned-center">
                        <div class="box-dark-element-custom">
                            <input class="input-custom" value="<?=$contact['PHONE_NUMBER']?>">
                        </div>
                        <?php if ($i !== 0 ): ?>
                            <button class="button-plus-minus button-small-custom">-</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <button class="button-plus-minus button-large-custom">+</button>
            </div>
            <div class="container-contacts">
                <label class="label">VK</label>
                <?php foreach ($arResult['user']['contacts']['vk'] as $contact): ?>
                    <div class="container-row-custom items-aligned-center">
                        <div class="box-dark-element-custom">
                            <input class="input-custom" value="<?=$contact['VK_PROFILE']?>">
                        </div>
                    </div>
                <?php endforeach; ?>
                <button class="button-plus-minus button-large-custom">+</button>
            </div>
            <div class="container-contacts">
                <label class="label">Telegram</label>
                <?php foreach ($arResult['user']['contacts']['telegram'] as $contact): ?>
                    <div class="container-row-custom items-aligned-center">
                        <div class="box-dark-element-custom">
                            <input class="input-custom" value="<?=$contact['TELEGRAM_USERNAME']?>">
                        </div>
                    </div>
                <?php endforeach; ?>
                <button class="button-plus-minus button-large-custom">+</button>
            </div>
        </div>
    </div>
    <div class="container-large-custom">
        <label class="label">Description</label>
        <div class="box-dark-element-custom">
            <textarea class="textarea-custom" placeholder="No description"><?=$arResult['user']['mainData']['DESCRIPTION']?></textarea>
        </div>
        <div class="container-row-custom">
            <div class="container-column-custom">
                <div class="container-column-custom">
                    <label class="label">Education format</label>
                    <div class="control">
                        <div class="select-custom">
                            <select name="education_format">
                                <?php foreach ($arResult['edFormats'] as $edFormat): ?>
                                    <option class="box" value="<?=$edFormat['ID']?>"><?=$edFormat['NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="container-column-custom">
                    <label class="label">City</label>
                    <div class="box-dark-element-custom max-width-90">
                        <input class="input-custom" placeholder="No city" value="<?=$arResult['user']['mainData']['CITY']?>">
                    </div>
                </div>
                <div class="container-column-custom">
                    <label class="label">Subjects</label>
                    <div class="box max-width-90">
                        <?php foreach ($arResult['user']['subjects'] as $subject): ?>
                        <div class="container-subjects">
                            <div class="container-subjects">
                                <div class="box-dark-element-custom">
                                    <?=$subject['SUBJECT']['NAME']?>
                                </div>
                                <div class="box-invisible-price-custom">
                                    <?=$subject['PRICE']?> rub/hour
                                </div>
                            </div>
                                <button class="button-plus-minus button-large-custom">-</button>
                        </div>
                        <?php endforeach; ?>
                        <?php if(count($arResult['user']['subjects']) !== 0): ?>
                            <div class="br"></div>
                        <?php endif; ?>
                        <div class="container-contacts">
                            <button class="button-plus-minus button-large-custom">+</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-column-custom">
                <label class="label">Days of week</label>
                <div class="box-stretched-custom">
                    <?php foreach ($arResult['weekdays'] as $weekday): ?>
                        <button class="box-button" onclick="getTime(<?=$arResult['user']['mainData']['ID']?>, <?=$weekday['ID']?>)"><?=$weekday['NAME']?></button>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="container-column-custom">
                <label class="label">Available time</label>
                <div class="container-time-custom">
                <div class="box-time-custom is-aligned-center" id="free-time-area">
                    <div>No time selected</div>
                </div>
                <div class="box-time-custom is-aligned-center">
                    <button class="button-plus-minus button-large-custom" id="add-time-button" onclick="showTimepicker()">+</button>
                    <div id="timepicker-form">
                        <form class="timepicker">
                            <div class="formfield">
                                <label>From:</label>
                                <input type="time" min="00:00" max="23:59"/>
                            </div>

                            <div class="formfield">
                                <label>To:</label>
                                <input type="time" min="00:00" max="23:59"/>
                            </div>
                        </form>
                        <button class="button-plus-minus button-small-custom" onclick="closeTimepicker()">OK</button>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
