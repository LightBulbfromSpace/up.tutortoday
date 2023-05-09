<?php
/**
 * @var array $arResult
 */

use Up\Tutortoday\Services\HTMLHelper;

global $USER;

\Bitrix\Main\UI\Extension::load('main.core');
\Bitrix\Main\UI\Extension::load('up.overview-popup');

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div id="message-area"></div>
<div class="container-fluid-custom main-container-custom">
    <div class="container-main-body-custom ml-0">
        <div class="container-column-custom mr-3">
            <div class="sidebar mr-2">
            <form method="get" action="/overview/">
                <div class="mt-5 container-row-custom space-between">
                    <div class="dropdown-custom w-47">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="subject-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Subjects
                        </button>
                        <div class="dropdown-menu" aria-labelledby="subject-dropdown">
                            <div class="form-group">
                                <?php foreach ($arResult['subjects'] as $subject) : ?>
                                <div class="form-check form-check-custom">
                                    <input class="form-check-input" name="subjects[]" type="checkbox" value="<?= $subject['ID']?>"
                                        <?=in_array($subject['ID'], (array)$arResult['filters']['subjects']) ? 'checked' : ''?>>
                                    <label class="form-check-label" for="<?=htmlspecialchars($subject['NAME'])?>">
                                        <?=htmlspecialchars($subject['NAME'])?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-custom w-47">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="city-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            City
                        </button>
                        <div class="dropdown-menu" aria-labelledby="subject-dropdown">
                            <div class="form-group">
                                <?php foreach ($arResult['cities'] as $city) : ?>
                                    <div class="form-check form-check-custom">
                                        <input class="form-check-input" name="cities[]" type="checkbox" value="<?= $city['ID']?>"
                                            <?=in_array($city['ID'], (array)$arResult['filters']['cities']) ? 'checked' : ''?>>
                                        <label class="form-check-label" for="<?=htmlspecialchars($city['NAME'])?>">
                                            <?=htmlspecialchars($city['NAME'])?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="mt-3 w-100">
                    <div class="dropdown-custom">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="ed-format-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Education Format
                        </button>
                        <div class="dropdown-menu" aria-labelledby="ed-format-dropdown">
                            <div class="form-group">
                                <?php foreach ($arResult['edFormats'] as $edFormat) : ?>
                                    <div class="form-check form-check-custom">
                                        <input class="form-check-input" name="edFormats[]" type="checkbox" value="<?= $edFormat['ID']?>"
                                            <?=in_array($edFormat['ID'], (array)$arResult['filters']['edFormats']) ? 'checked' : ''?>>
                                        <label class="form-check-label" for="<?=htmlspecialchars($edFormat['NAME'])?>">
                                            <?=htmlspecialchars($edFormat['NAME'])?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-row-custom space-between mt-3">
                    <div class="w-47">
                        <label for="price-from">The lowest&nbsp;price:</label>
                        <input type="text" class="form-control" name="minPrice" id="price-from" placeholder="Enter price"
                               value="<?=htmlspecialchars($arResult['filters']['minPrice'])?>">
                    </div>
                    <div class="w-47">
                        <label for="price-to">The highest&nbsp;price:</label>
                        <input type="text" class="form-control" name="maxPrice" id="price-to" placeholder="Enter price"
                               value="<?=htmlspecialchars($arResult['filters']['maxPrice'])?>">
                    </div>
                </div>
                <?php if ($USER->GetID() !== null): ?>
                    <div class="form-check form-check-custom mt-4 pl-0-3-rem">
                        <input class="form-check-input" name="myPreferences" type="checkbox"
                            <?=$arResult['myPreferences'] ? 'checked' : ''?>>
                        <label class="form-check-label" for="myPreferences">
                            Use my preferences
                        </label>
                    </div>
                <?php endif; ?>
                <div class="container-row-custom space-between mb-5">
                    <button type="submit" id="findButton" class="btn mt-4 w-47 btn-danger">Find</button>
                    <a class="btn mt-4 w-47 btn-danger" href="/overview/">Reset</a>
                </div>
            </form>
        </div>
            <div class="sidebar mr-2 mt-5-custom pl-0 pr-0">
                <div class="message-header-custom">
                    Facts & Useful
                </div>
                <div class="message-body-custom p-2">
                    <div class="sub-message-custom padding-1">
                        “If you want to be able to remember information, the best thing you can do is practice,” Katherine Rawson, psychologist at Kent State University in Ohio.
                    </div>
                    <div class="br-message"></div>
                    <div class="sub-message-custom padding-1">Pay attention to diagrams and graphs in your class materials. Those pictures can really boost your memory of this material. And if there aren’t pictures, creating them can be really useful.</div>
                    <div class="br-message"></div>
                    <div class="sub-message-custom padding-1">Search for examples. Abstract concepts can be hard to understand. It tends to be far easier to form a mental image if you have a concrete example of something</div>
                    <div class="br-message"></div>
                    <div class="sub-message-custom padding-1">Have a question? Contact us by <?=SUPPORT_EMAIL?></div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
                <form method="get" class="form-inline my-lg-0 search-custom" action="/overview/">
                    <input class="form-control mr-sm-2 search-input-custom" type="search" placeholder="Find tutor" name="search">
                    <button class="btn btn-danger my-2 my-sm-0" type="submit">Search</button>
                </form>

            <p class="mt-3">Recently created profiles:</p>
            <?php if (count($arResult['tutors']) === 0): ?>
                <div class="not-found-container">
                    <div class="not-found-text">
                        No tutors found
                    </div>
                </div>
            <?php endif; ?>
            <div class="cards-container">
                <?php foreach ($arResult['tutors'] as $tutor) : ?>
                <div class="mb-3 card-link-container-custom">
                    <a class="mt-4 card-box-container-custom" href="/profile/<?=$tutor['ID']?>/">
                        <div class="row no-gutters card-container">
                                <div class="photo-container">
                                    <img src="<?=$tutor['photo']?>" class="img-rounded card-img img-fixed-size" alt="Tutor photo">
                                </div>
                            <div class="card-body-custom">
                                    <h2 class="card-title-custom">
                                        <?=htmlspecialchars(HTMLHelper::cutText("{$tutor['fullName']['lastName']} {$tutor['fullName']['name']} {$tutor['fullName']['secondName']}", 35))?>
                                    </h2>
<!--                                    <div class="br"></div>-->
                                <div class="container-card-main-info-custom">
                                    <div class="subjects-ed-format-container">
                                        <div class="city-container">
                                                <img src="/local/components/up/tutortoday.overview/templates/.default/icons/icons8-location-50.png" class="icon-custom" alt="location">
                                                <?php if($tutor['city'] == null): ?>
                                                    No city
                                                <?php endif; ?>
                                                <?=htmlspecialchars($tutor['city']['NAME'])?>
                                        </div>
                                        <div class="container-subjects">
                                            <?php if($tutor['subjects'] == null): ?>
                                                <div class="box-darker-element-custom">No subjects</div>
                                            <?php endif; ?>
                                            <?php foreach ($tutor['subjects'] as $i => $subject): ?>
                                            <?php if ($i === 2): ?>
                                                    <div class="box-darker-element-custom container-row-custom is-justified-center">
                                                        ...
                                                    </div>
                                            <?php break; endif; ?>
                                                <div class="box-darker-element-custom container-row-custom is-justified-center">
                                                    <div><?=htmlspecialchars($subject['NAME'])?></div>
                                                    <div class="vbr"></div>
                                                    <div><?=$subject['PRICE'] == 0 ? '-' : htmlspecialchars($subject['PRICE'])?></div>
                                                </div>
                                            <?php endforeach; ?>
                                            <?php if($tutor['edFormat'] == null): ?>
                                                <div class="box-dark-element-custom">No education format</div>
                                            <?php endif; ?>
                                            <?php foreach ($tutor['edFormat'] as $edFormat): ?>
                                                <div class="box-dark-element-custom"><?=htmlspecialchars($edFormat['NAME'])?></div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="card-text-container">
                                        <p class="card-text-custom">
                                            &nbsp;
                                            <?php if($tutor['description'] == ''): ?>
                                                No description
                                            <?php endif; ?>
                                            <?=htmlspecialchars(HTMLHelper::cutText($tutor['description'], 155))?>
                                        </p>
                                    </div>
                                </div>
                                </div>

                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
<!--            pagination-->
            <div class="container-margin-top is-justified-center">
                <nav role="navigation" class="container-row-custom">
                    <ul class="pagination-list">
                        <li>
                            <a class="pagination-link" href="/overview/?page=1">1</a>
                        </li>
                        <li>
                            <span class="pagination-ellipsis">&hellip;</span>
                        </li>
                        <li>
                            <a class="pagination-link"
                                <?=($arResult['currentPage'] - 1) > 0 ?
                                    'href="/overview/?'. $arResult['currentURIParams'] . '&page=' . $arResult['currentPage'] - 1 . '"' : ''?>>
                                <?=($arResult['currentPage'] - 1) > 0 ?
                                    $arResult['currentPage'] - 1 : ''?>
                            </a>
                        </li>
                        <li>
                            <a class="pagination-link is-current-custom" href="/overview/?<?=$arResult['currentURIParams']?>&page=<?=$arResult['currentPage']?>"><?=$arResult['currentPage']?></a>
                        </li>
                        <li>
                            <a class="pagination-link"
                                <?=($arResult['currentPage'] + 1) <= $arResult['maxPage'] ?
                                'href="/overview/?'. $arResult['currentURIParams'] . '&page=' . $arResult['currentPage'] + 1 . '"' : ''?>>
                                <?=($arResult['currentPage'] + 1) <= $arResult['maxPage'] ?
                                    $arResult['currentPage'] + 1 : ''?>
                            </a>
                        </li>
                        <li>
                            <span class="pagination-ellipsis">&hellip;</span>
                        </li>
                        <li>
                            <a class="pagination-link" href="/overview/?<?=$arResult['currentURIParams']?>&page=<?=$arResult['maxPage']?>"><?=$arResult['maxPage']?></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<script>
    BX.ready(() => {
        let elems = document.getElementsByClassName('tablebodytext')
        if (elems[0]) {
            elems[0].remove()
        }
        window.filtersOverviewPopupTutortoday = new BX.Up.Tutortoday.OverviewPopup({
            nodeID: 'message-area',
            edFormats: <?=isset($arResult['filters']['edFormats']) ? 'true' : 'false'?>,
            subjects: <?=isset($arResult['filters']['subjects']) ? 'true' : 'false'?>,
            city: <?=isset($arResult['filters']['cities']) ? 'true' : 'false'?>,
            price: <?=isset($arResult['filters']['maxPrice']) || isset($arResult['filters']['minPrice']) ? 'true' : 'false'?>,
            preferences: <?=$arResult['myPreferences'] ? 'true' : 'false'?>,
        })
        window.filtersOverviewPopupTutortoday.displayMessage();
    })
</script>

