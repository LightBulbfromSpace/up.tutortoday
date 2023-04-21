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
                    <?php foreach ($arResult['user']['time'] as $weekday => $times): ?>
                        <?php var_dump(json_encode($times));?>
                        <button class="box" onclick="showTime('<?=$weekday?>', `<?=json_encode($times)?>`)"><?=$weekday?></button>
                    <?php endforeach; ?>
<!--                    <button class="box" onclick="showTime('tuesday')">Tuesday</button>-->
<!--                    <button class="box" onclick="showTime('wednesday')">Wednesday</button>-->
<!--                    <button class="box" onclick="showTime('thursday')">Thursday</button>-->
<!--                    <button class="box" onclick="showTime('friday')">Friday</button>-->
<!--                    <button class="box" onclick="showTime('saturday')">Saturday</button>-->
<!--                    <button class="box" onclick="showTime('sunday')">Sunday</button>-->
                </div>
            </div>
            <div class="container-column-custom">
                <label class="label">Free hours</label>
                <div class="box-stretched-custom" id="">
                    Free hours
                </div>
            </div>
            </div>
        </div>
    </div>
</div>