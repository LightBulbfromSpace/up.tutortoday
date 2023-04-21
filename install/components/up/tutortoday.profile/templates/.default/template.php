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
<script src="/local/components/up/tutortoday.profile/templates/.default/scripts.js"></script>
<div class="container-custom">
    <div class="container-narrow-custom">
        <div class="box">
            <img src="<?=$arResult['user']['photo']?>" class="img-rounded" alt="avatar">
            <div class="box-dark-element-custom">
                <?=$arResult['user']['mainData']['NAME']?>
                <?=$arResult['user']['mainData']['SURNAME']?>
                <?=$arResult['user']['mainData']['MIDDLE_NAME']?>
            </div>
            <div class="box-invisible-custom">I'm a <?=$arResult['user']['mainData']['ROLE']['NAME']?></div>
            <div class="br"></div>
            <?php foreach ($arResult['user']['contacts'] as $contact): ?>
                <?php if ($contact['EMAIL'] != null): ?>
                    <div class="box-dark-element-custom">
                        <?=$contact['EMAIL']?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php foreach ($arResult['user']['contacts'] as $contact): ?>
                <?php if ($contact['PHONE_NUMBER'] != null): ?>
                    <div class="box-dark-element-custom">
                        <?=$contact['PHONE_NUMBER']?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php foreach ($arResult['user']['contacts'] as $contact): ?>
                <?php if ($contact['VK_PROFILE'] != null): ?>
                    <div class="box-dark-element-custom">
                        <?=$contact['VK_PROFILE']?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php foreach ($arResult['user']['contacts'] as $contact): ?>
                <?php if ($contact['TELEGRAM_USERNAME'] != null): ?>
                    <div class="box-dark-element-custom">
                        <?=$contact['TELEGRAM_USERNAME']?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="container-large-custom">
        <label class="label">Description</label>
        <div class="box">
            <?php if($arResult['user']['mainData']['DESCRIPTION'] === ''): ?>
                No description
            <?php endif; ?>
            <?=$arResult['user']['mainData']['DESCRIPTION']?>
        </div>
        <div class="container-row-custom">
            <div class="container-column-custom">
                <div class="container-column-custom">
                    <label class="label">Education format</label>
                    <div class="box">
                        <?=$arResult['user']['mainData']['EDUCATION_FORMAT']['NAME']?>
                    </div>
                </div>
                <div class="container-column-custom">
                    <label class="label">City</label>
                    <div class="box">
                        <?php if($arResult['user']['mainData']['CITY'] === ''): ?>
                            No city selected
                        <?php endif; ?>
                        <?=$arResult['user']['mainData']['CITY']?>
                    </div>
                </div>
                <div class="container-column-custom">
                    <label class="label">Subjects</label>
                    <div class="box">
                        <?php if(count($arResult['user']['subjects']) === 0): ?>
                            No subjects selected
                        <?php endif; ?>
                        <?php foreach ($arResult['user']['subjects'] as $subject): ?>
                            <div class="box-dark-element-custom">
                                <?=$subject['SUBJECT']['NAME']?>
                            </div>
                            <div class="box-invisible-custom">
                                <?=$subject['PRICE']?> rub/hour
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="container-column-custom">
                <label class="label">Days of week</label>
                <div class="box-stretched-custom">
                    <?php foreach ($arResult['weekdays'] as $weekday): ?>
                        <button class="box-button" id="weekday-<?=$weekday['ID']?>"><?=$weekday['NAME']?></button>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="container-column-custom">
                <label class="label">Available time</label>
                <div class="box-stretched-custom is-aligned-center" id="free-time-area">
                    <div>No time selected</div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    let weekdays = [];
    for (let i = 1; i < 8; i++) {
        weekdays.push(BX('weekday-' + i))
    }
    for (let i = 0; i < 7; i++) {
        BX.bind(weekdays[i], 'click', () => {
            BX.ajax({
                url: '/profile/weekday/',
                data: {
                    userID: <?=$arResult['user']['mainData']['ID']?>,
                    weekdayID: i+1,
                    sessid: BX.bitrix_sessid(),
                },
                method: 'POST',
                dataType: 'json',
                timeout: 10,
                onsuccess: function (res) {
                    console.log(res);
                    if (res != null) {
                        let area = document.getElementById('free-time-area')
                        while (area.lastElementChild) {
                            area.removeChild(area.lastElementChild);
                        }

                        if (res.length === 0) {
                            let divElem = document.createElement('div');
                            divElem.innerText = 'No time selected';
                            area.appendChild(divElem);
                        } else {
                            res.forEach((interval) => {
                                let divElem = document.createElement('div');
                                divElem.innerText = interval['start'] + ' - ' + interval['end'];
                                area.appendChild(divElem);
                            });
                        }
                    }
                },
                onfailure: e => {
                    console.error(e)
                }
            })
        })
    }
</script>
