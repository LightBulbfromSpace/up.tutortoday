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
    <div class="container-row-custom ml-0">
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
                                    <input class="form-check-input" name="subjects[]" type="checkbox" value="<?= $subject['ID']?>">
                                    <label class="form-check-label" for="<?= $subject['NAME']?>">
                                        <?= $subject['NAME']?>
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
                                        <input class="form-check-input" name="cities[]" type="checkbox" value="<?= $city['ID']?>">
                                        <label class="form-check-label" for="<?= $city['NAME']?>">
                                            <?= $city['NAME']?>
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
                                        <input class="form-check-input" name="edFormats[]" type="checkbox" value="<?= $edFormat['ID']?>">
                                        <label class="form-check-label" for="<?= $edFormat['NAME']?>">
                                            <?= $edFormat['NAME']?>
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
                        <input type="text" class="form-control" name="minPrice" id="price-from" placeholder="Enter price">
                    </div>
                    <div class="w-47">
                        <label for="price-to">The highest&nbsp;price:</label>
                        <input type="text" class="form-control" name="maxPrice" id="price-to" placeholder="Enter price">
                    </div>
                </div>
                <?php if ($USER->GetID() !== null): ?>
                    <div class="form-check form-check-custom mt-4 pl-0-3-rem">
                        <input class="form-check-input" name="myPreferences" type="checkbox">
                        <label class="form-check-label" for="myPreferences">
                            Use my preferences
                        </label>
                    </div>
                <?php endif; ?>
                <div class="container-row-custom space-between mb-5">
                    <button type="submit" id="findButton" class="btn mt-4 w-47 btn-danger">Find</button>
                    <a class="btn mt-4 w-47 btn-danger" href="/">Reset</a>
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
                    <div class="sub-message-custom padding-1">Have a question? Contact us by support.email@email.ru</div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
                <form method="get" class="form-inline my-lg-0" action="/">
                    <input class="form-control mr-sm-2" type="search" placeholder="Find tutor" name="search">
                    <button class="btn btn-danger my-2 my-sm-0" type="submit">Search</button>
                </form>

            <p class="mt-3">Resently created profiles:</p>
			<?php foreach ($arResult['tutors'] as $tutor) : ?>
            <a href="/profile/<?=$tutor['ID']?>/" class="card-link">
                <div class="mt-2 card-box-container-custom">
                    <div class="row no-gutters card-container">
                        <div class="col-md-4 photo-container">
                            <img src="<?=$tutor['photo']?>" class="img-rounded card-img img-fixed-size" alt="Tutor photo">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body-custom">
                                <h2 class="card-title-custom">
                                    <?=htmlspecialchars($tutor['fullName']['lastName'])?>&nbsp;
                                    <?=htmlspecialchars($tutor['fullName']['name'])?>&nbsp;
                                    <?=htmlspecialchars($tutor['fullName']['secondName'])?></h2>
                                <div class="br"></div>
                                <p class="card-text">
                                    <strong>City:</strong>
                                    <?php if($tutor['city'] == null): ?>
                                        No city
                                    <?php endif; ?>
                                    <?=htmlspecialchars($tutor['city']['NAME'])?>
                                </p>
                                <div class="container-subjects">
                                    <?php if($tutor['subjects'] == null): ?>
                                        <div class="box-darker-element-custom">No subjects</div>
                                    <?php endif; ?>
                                    <?php foreach ($tutor['subjects'] as $subject): ?>
                                        <div class="box-darker-element-custom container-row-custom is-justified-center">
                                            <div><?=$subject['NAME']?></div>
                                            <div class="vbr"></div>
                                            <div><?=$subject['PRICE'] == 0 ? '-' : $subject['PRICE']?></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="container-subjects">
                                    <?php if($tutor['edFormat'] == null): ?>
                                        <div class="box-dark-element-custom">No education format</div>
                                    <?php endif; ?>
                                    <?php foreach ($tutor['edFormat'] as $edFormat): ?>
                                        <div class="box-dark-element-custom"><?=$edFormat['NAME']?></div>
                                    <?php endforeach; ?>
                                </div>
                                <p class="card-text">
                                    <strong>Description:</strong>
                                    &nbsp;
                                    <?php if($tutor['description'] == ''): ?>
                                        No description
                                    <?php endif; ?>
                                    <?=htmlspecialchars(HTMLHelper::cutText($tutor['description'], 300))?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
			<?php endforeach; ?>
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
        window.filtersOverviewPopupTutortoday.displayMessage()
    })
</script>

