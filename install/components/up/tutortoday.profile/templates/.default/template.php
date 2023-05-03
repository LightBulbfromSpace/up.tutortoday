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
<div class="container-custom">
    <div class="container-narrow-custom">
        <div class="box">
            <img src="<?=$arResult['user']['photo']?>" class="img-rounded img-fixed-size" alt="avatar">
            <div class="box-dark-element-custom">
                <?=htmlspecialchars($arResult['user']['mainData']['NAME'])?>
                <?=htmlspecialchars($arResult['user']['mainData']['LAST_NAME'])?>
                <?=htmlspecialchars($arResult['user']['mainData']['SECOND_NAME'])?>
            </div>
            <div class="box-invisible-custom role-container-custom"><div>I'm a</div>&nbsp;<div class="box-small-dark-custom"><?=$arResult['user']['role']['NAME']?></div></div>
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
            <?php if (count($arResult['user']['contacts']['vk']) === 0): ?>
                <div class="box-dark-element-custom">No VK profile</div>
            <?php endif; ?>
            <?php foreach ($arResult['user']['contacts']['vk'] as $contact): ?>
                <div class="box-dark-element-custom">
                    <?=htmlspecialchars($contact['VK_PROFILE'])?>
                </div>
            <?php endforeach; ?>
            <label class="label">Telegram</label>
            <?php if (count($arResult['user']['contacts']['telegram']) === 0): ?>
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
        <div class="container-button-custom">
            <div class="save-button-container">
                <a class="button-plus-minus link-button button-small-container-custom" href="/profile/<?=$USER->GetID()?>/settings/">Settings</a>
            </div>
            <div class="save-button-container">
                <a class="button-plus-minus link-button button-small-container-custom" href="/logout/">Logout</a>
            </div>
        </div>
        <?php endif; ?>
        <label class="label">Description</label>
        <div class="box">
            <?php if($arResult['user']['description'] == ''): ?>
                No description
            <?php endif; ?>
            <?=htmlspecialchars($arResult['user']['description'])?>
        </div>
        <div class="container-large-column-custom">
            <div class="container-row-custom">
                <div class="container-column-custom">
                    <label class="label">Days of week</label>
                    <div class="box-stretched-custom">
                        <?php foreach ($arResult['weekdays'] as $weekday): ?>
                            <button class="box-button" onclick="getTime(<?=$arResult['user']['mainData']['ID']?>, <?=$weekday['ID']?>)" id="weekday-<?=$weekday['ID']?>"><?=$weekday['NAME']?></button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="container-column-custom">
                    <div class="container-column-custom">
                        <label class="label">Available time</label>
                        <div class="box-stretched-custom is-aligned-center" id="free-time-area">
                            <div>Select the weekday</div>
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
                    <div class="container-column-custom">
                        <label class="label">Education formats</label>
                        <div class="box">
                            <?php if(count($arResult['user']['edFormats']) === 0): ?>
                                No formats selected
                            <?php endif; ?>
                            <?php foreach ($arResult['user']['edFormats'] as $edFormat): ?>
                                <div class="box-dark-element-custom">
                                    <?=$edFormat['EDUCATION_FORMAT']['NAME']?>
                                </div>
                            <?php endforeach; ?>
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

                </div>

            </div>
            <div class="feedbacks-container" id="feedbacks-container">
                <?php if ($arResult['user']['observer']['role']['NAME'] !== 'tutor'): ?>
                    <?php if ($USER->GetID() === null): ?>
                        <div class="box">Only logged-in users can see and send feedbacks</div>
                    <?php else: ?>
                        <?php if ($arResult['user']['role']['NAME'] === 'tutor'): ?>
                            <button type="button" id="add-close-feedback-button" class="box-button">Add feedback</button>
                            <div id="feedback-form-area"></div>
<!--                            --><?php //if (count($arResult['user']['feedbacks']) === 0): ?>
<!--                                <div class="box" id="no-feedbacks-message">No feedbacks yet</div>-->
<!--                            --><?php //endif; ?>
                            <div id="feedbacks-area">
<!--                            --><?php //if (count($arResult['user']['feedbacks']) !== 0): ?>
<!--                                <button class="feedback-button">&lt;</button>-->
<!--                            --><?php //endif; ?>
<!--                                <div class="feedback-cards-container">-->
<!--                                --><?php //foreach ($arResult['user']['feedbacks'] as $i => $feedback): ?>
<!--                                <div class="feedback-card-container">-->
<!--                                    <a class="feedback-card-user-info-container" href="/profile/--><?php //=$feedback['student']['ID']?><!--/">-->
<!--                                        <img src="--><?php //=$feedback['student']['photo']?><!--" class="photo-small img-rounded" alt="avatar">-->
<!--                                        <div class="help">--><?php //=htmlspecialchars($feedback['student']['surname'])?><!--</div>-->
<!--                                        <div class="help">--><?php //=htmlspecialchars($feedback['student']['name'])?><!--</div>-->
<!--                                    </a>-->
<!--                                    <div class="box feedback-card-custom">-->
<!--                                        <div class="title-feedback-custom">-->
<!--                                            <div class="title-custom">--><?php //=htmlspecialchars($feedback['title'])?><!--</div>-->
<!--                                            <div class="stars-container">-->
<!--                                                --><?php //for ($j = 5; $j > 0; $j--): ?>
<!--                                                    <div id="s--><?php //=$j?><!-----><?php //=$i?><!---disabled" class="fa fa-star --><?php //= $j<=$feedback['stars'] ? 'star-selected' : '' ?><!--"></div>-->
<!--                                                --><?php //endfor; ?>
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                        <div class="br"></div>-->
<!--                                        --><?php //if ($feedback['description'] == ''): ?>
<!--                                            <div>No description</div>-->
<!--                                        --><?php //endif; ?>
<!--                                        <div>--><?php //=htmlspecialchars($feedback['description'])?><!--</div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                --><?php //endforeach; ?>
<!--                                </div>-->
<!--                            --><?php //if (count($arResult['user']['feedbacks']) !== 0): ?>
<!--                                <button class="feedback-button">&gt;</button>-->
<!--                            --><?php //endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                <?php endif; ?>
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
    })
</script>