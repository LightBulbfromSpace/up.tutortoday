<?php
/**
 * @var array $arResult
 */

global $USER;

\Bitrix\Main\UI\Extension::load('main.core');
\Bitrix\Main\UI\Extension::load('up.feedbacks');

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="/local/components/up/tutortoday.profile/templates/.default/scripts.js"></script>
<div class="main-container">
<div class="container-custom">
    <div class="container-narrow-custom">
        <div class="box round-corners">
            <div class="photo-container">
                <img src="<?=$arResult['user']['photo']?>" class="img-rounded img-fixed-size" alt="avatar">
            </div>
            <div class="card-text-custom card-title-custom cards-background">
                <?=htmlspecialchars($arResult['user']['mainData']['NAME'])?>
                <?=htmlspecialchars($arResult['user']['mainData']['LAST_NAME'])?>
                <?=htmlspecialchars($arResult['user']['mainData']['SECOND_NAME'])?>
            </div>
            <div class="box-invisible-custom role-container-custom">
                <div>I'm a</div>
                &nbsp;
                <div class="box-small-dark-custom cards-background">
                    <?=htmlspecialchars($arResult['user']['role']['NAME'])?>
                </div>
            </div>
            <div class="br"></div>
            <?php if ($USER->GetID() === null): ?>
                <div class="box-dark-element-custom">Only logged-in users can see contacts</div>
            <?php else: ?>
                <label class="label ml-2">Email</label>
                <?php if ($arResult['user']['contacts']['email'] == null): ?>
                    <div class="card-text-custom card-title-custom">No email</div>
                <?php else: ?>
                    <div class="card-text-custom card-title-custom">
                        <?=htmlspecialchars($arResult['user']['contacts']['email'])?>
                    </div>
                <?php endif; ?>
                <label class="label ml-2">Phone</label>
                <?php if ($arResult['user']['contacts']['phone'] == null): ?>
                    <div class="card-text-custom card-title-custom">No phone</div>
                <?php else: ?>
                    <div class="card-text-custom card-title-custom">
                        <?=htmlspecialchars($arResult['user']['contacts']['phone'])?>
                    </div>
                <?php endif; ?>
                <label class="label ml-2 hidden">VK</label>
                <?php if (count($arResult['user']['contacts']['vk']) === 0): ?>
                    <div class="card-text-custom card-title-custom hidden">No VK profile</div>
                <?php endif; ?>
                <?php foreach ($arResult['user']['contacts']['vk'] as $contact): ?>
                    <div class="card-text-custom card-title-custom hidden">
                        <?=htmlspecialchars($contact['VK_PROFILE'])?>
                    </div>
                <?php endforeach; ?>
                <label class="label ml-2 hidden">Telegram</label>
                <?php if (count($arResult['user']['contacts']['telegram']) === 0): ?>
                    <div class="card-text-custom card-title-custom hidden">No telegram username</div>
                <?php endif; ?>
                <?php foreach ($arResult['user']['contacts']['telegram'] as $contact): ?>
                    <div class="card-text-custom card-title-custom hidden">
                        <?=htmlspecialchars($contact['TELEGRAM_USERNAME'])?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="container-large-custom">
        <?php if($arResult['isOwner']): ?>
        <div class="container-button-custom">
            <div class="save-button-container">
                <a class="button-plus-minus link-button button-small-container-custom" id="settingsprof" href="/profile/<?=$USER->GetID()?>/settings/">Settings</a>
            </div>
            <div class="save-button-container">
                <a class="button-plus-minus link-button button-small-container-custom" href="/logout/">Logout</a>
            </div>
        </div>
        <?php endif; ?>
        <label class="label ml-2">Description</label>
        <div class="box round-corners card-text-custom desc-background desc-text">
            <?php if($arResult['user']['description'] == ''): ?>
                No description
            <?php endif; ?>
            <?=htmlspecialchars($arResult['user']['description'])?>
        </div>
        <div class="container-large-column-custom">
            <div class="container-widgets-custom">
                <div class="container-column-custom">
                    <label class="label ml-2">Days of week</label>
                    <div class="box-stretched-custom round-corners">
                        <?php foreach ($arResult['weekdays'] as $weekday): ?>
                            <button class="box-button" onclick="getTime(<?=$weekday['ID']?>, <?=$arResult['ID']?>)" id="weekday-<?=$weekday['ID']?>"><?=htmlspecialchars($weekday['NAME'])?></button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="container-column-custom">
                    <div class="container-column-custom">
                        <label class="label ml-2">Available time</label>
                        <div class="box-stretched-custom is-aligned-center round-corners" id="free-time-area">
                            <div>Select the weekday</div>
                        </div>
                    </div>
                    <div class="container-column-custom">
                        <label class="label ml-2">Subjects</label>
                        <div class="box-stretched-custom is-aligned-center round-corners">
                            <?php if(count($arResult['user']['subjects']) === 0): ?>
                                No subjects selected
                            <?php endif; ?>
                            <?php foreach ($arResult['user']['subjects'] as $subject): ?>
                                <div class="card-text-custom w-100">
                                    <?=htmlspecialchars($subject['SUBJECT']['NAME'])?>
                                </div>
                                <div class="is-justified-center mt-1 mb-1">
                                    <?=htmlspecialchars($subject['PRICE'])?> rub/hour
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="container-column-custom">
                    <div class="container-column-custom">
                        <label class="label ml-2">Education formats</label>
                        <div class="box round-corners">
                            <?php if(count($arResult['user']['edFormats']) === 0): ?>
                                No formats selected
                            <?php endif; ?>
                            <?php foreach ($arResult['user']['edFormats'] as $edFormat): ?>
                                <div class="card-text-custom">
                                    <?=htmlspecialchars($edFormat['EDUCATION_FORMAT']['NAME'])?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="container-column-custom">
                        <label class="label ml-2">City</label>
                        <div class="box round-corners is-justified-center">
                            <?php if($arResult['user']['city'] == ''): ?>
                                No city selected
                            <?php endif; ?>
                            <?=htmlspecialchars($arResult['user']['city'])?>
                        </div>
                    </div>

                </div>

            </div>
            <div class="feedbacks-container" id="feedbacks-container">
                <?php if ($arResult['user']['observer']['role']['NAME'] !== 'tutor'): ?>
                    <?php if ($USER->GetID() === null): ?>
                        <div class="box">Only logged-in students can see and send feedbacks</div>
                    <?php else: ?>
                        <?php if ($arResult['user']['role']['NAME'] === 'tutor'): ?>
                            <button type="button" id="add-close-feedback-button" class="box-button">Add feedback</button>
                            <div id="feedback-form-area"></div>
                            <div id="feedbacks-area"></div>
                        <?php endif; ?>
                    <?php endif; ?>

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    BX.ready(() => {
        window.TutortodayFeedbacks = new BX.Up.Tutortoday.Feedbacks({
            rootNodeID: 'feedbacks-container',
            formID: 'feedback-form-area',
            feedbacksRootID: 'feedbacks-area',
            feedbackReceiverID: <?=$arResult['user']['mainData']['ID']?>,
            toggleButtonID: 'add-close-feedback-button',
        })
        window.TutortodayFeedbacks.loadFeedbacksPerPage()
        hideWarning()
    })
</script>