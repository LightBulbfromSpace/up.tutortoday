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
<script type="text/javascript" src="/local/components/up/tutortoday.profile/templates/.default/scripts.js"></script>
<div class="container-custom">
    <div class="container-narrow-custom">
        <div class="box">
            <img src="<?=$arResult['user']['photo']?>" class="img-rounded" alt="avatar">
            <div class="box-dark-element-custom">
                <?=htmlspecialchars($arResult['user']['mainData']['NAME'])?>
                <?=htmlspecialchars($arResult['user']['mainData']['LAST_NAME'])?>
                <?=htmlspecialchars($arResult['user']['mainData']['SECOND_NAME'])?>
            </div>
            <div class="box-invisible-custom">I'm a <?=$arResult['user']['role']['NAME']?></div>
            <div class="br"></div>
            <label class="label">Email</label>
            <?php if ($arResult['user']['contacts']['email'] == null): ?>
                <div class="box-dark-element-custom">No email</div>
            <?php else: ?>
                <div class="box-dark-element-custom">
                    <?=$arResult['user']['contacts']['email']?>
                </div>
            <?php endif; ?>
            <label class="label">Phone</label>
            <?php if ($arResult['user']['contacts']['phone'] == null): ?>
                <div class="box-dark-element-custom">No phone</div>
            <?php else: ?>
                <div class="box-dark-element-custom">
                    <?=$arResult['user']['contacts']['phone']?>
                </div>
            <?php endif; ?>
            <label class="label">VK</label>
            <?php if ($arResult['user']['contacts']['vk'] == null): ?>
                <div class="box-dark-element-custom">No VK profile</div>
            <?php endif; ?>
            <?php foreach ($arResult['user']['contacts']['vk'] as $contact): ?>
                <div class="box-dark-element-custom">
                    <?=htmlspecialchars($contact['VK_PROFILE'])?>
                </div>
            <?php endforeach; ?>
            <label class="label">Telegram</label>
            <?php if ($arResult['user']['contacts']['telegram'] == null): ?>
                <div class="box-dark-element-custom">No telegram username</div>
            <?php endif; ?>
            <?php foreach ($arResult['user']['contacts']['telegram'] as $contact): ?>
                <div class="box-dark-element-custom">
                    <?=htmlspecialchars($contact['TELEGRAM_USERNAME'])?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="container-large-custom">
        <?php if($arResult['isOwner']): ?>
            <div class="save-button-container">
                <a class="button-plus-minus link-button" href="/profile/<?=$arResult['user']['mainData']['ID']?>/settings/">Settings</a>
            </div>
        <?php endif; ?>
        <label class="label">Description</label>
        <div class="box">
            <?php if($arResult['user']['description'] == ''): ?>
                No description
            <?php endif; ?>
            <?=htmlspecialchars($arResult['user']['description'])?>
        </div>
        <div class="container-row-custom">
            <div class="container-column-custom">
                <div class="container-column-custom">
                    <label class="label">Education format</label>
                    <div class="box">
                        <?=$arResult['user']['edFormat']['NAME']?>
                    </div>
                </div>
                <div class="container-column-custom">
                    <label class="label">City</label>
                    <div class="box">
                        <?php if($arResult['user']['city'] == ''): ?>
                            No city selected
                        <?php endif; ?>
                        <?=htmlspecialchars($arResult['user']['city'])?>
                    </div>
                </div>
                <div class="container-column-custom">
                    <label class="label">Subjects</label>
                    <div class="box">
                        <?php if($arResult['user']['subjects'] == null): ?>
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
                        <button class="box-button" onclick="getTime(<?=$arResult['user']['mainData']['ID']?>, <?=$weekday['ID']?>)" id="weekday-<?=$weekday['ID']?>"><?=$weekday['NAME']?></button>
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
<!--<script type="text/javascript" src="/local/components/up/tutortoday.profile/templates/.default/scripts.js">-->
<!--   for (let i = 1; i < 8; i++) {-->
<!--        BX.bind(BX('weekday-' + i), 'click', () => {-->
<!--            getTime(--><?php ////=$arResult['user']['mainData']['ID']?><!--//, i)-->
<!--//        })-->
<!--//    }-->
<!--//</script>-->