<?php
/**
 * @var array $arResult
 */

//var_dump($arResult['tutors']['mainData']);die;
use Up\Tutortoday\Services\HTMLHelper;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<!--<div class="container-main-custom">-->
<!--    <div class="container-custom">-->
<!--        <form action="/" method="post">-->
<!--            filters-->
<!--            --><?php //=bitrix_sessid_post()?>
<!--        </form>-->
<!--    </div>-->
<!--    <div class="container-custom">-->
<!--        <form action="/" method="get">-->
<!--            search-->
<!--            --><?php //=bitrix_sessid_post()?>
<!--        </form>-->
<!--        <div class="container-content-custom"></div>-->
<!--    </div>-->
<!--</div>-->

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 bg-light sidebar">
            <form method="get" action="/">
                <div class="col-md-9 mt-3">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="subject-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Education Format
                        </button>
                        <div class="dropdown-menu" aria-labelledby="subject-dropdown">
                            <div class="form-group">
	                            <?php foreach ($arResult['edFormats'] as $edFormat) : ?>
                                    <div class="form-check">
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
                <div class="col-md-9 mt-3">
                    <div class="dropdown">
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
                </div>
                <div class="row mt-3 ml-1">
                    <div class="col">
                        <label for="price-from">The lowest price:</label>
                        <input type="text" class="form-control" name="minPrice" id="price-from" placeholder="Enter price">
                    </div>
                    <div class="col">
                        <label for="price-to">The highest price:</label>
                        <input type="text" class="form-control" name="maxPrice" id="price-to" placeholder="Enter price">
                    </div>
                </div>
                <button type="submit" class="btn mt-6 ml-3 btn-danger">Find</button>
            </form>
        </div>
        <div class="col-md-9">
            <div class="mt-3">
                <form method="post" class="form-inline my-2 my-lg-0" action="/search/">
                    <input class="form-control mr-sm-2" type="search" placeholder="Find tutor" aria-label="Search">
                    <button class="btn btn-danger my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>

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
                                <h2 class="card-title">
                                    <?=htmlspecialchars($tutor['fullName']['lastName'])?>&nbsp;&nbsp;
                                    <?=htmlspecialchars($tutor['fullName']['name'])?>&nbsp;&nbsp;
                                    <?=htmlspecialchars($tutor['fullName']['secondName'])?></h2>
                                <div class="br"></div>
                                <p class="card-text">
                                    <strong>City:</strong>
                                    &nbsp;
                                    <?php if($tutor['city'] == ''): ?>
                                        No city
                                    <?php endif; ?>
                                    <?=htmlspecialchars($tutor['city'])?>
                                </p>
                                <div class="container-subjects">
                                    <?php if($tutor['subjects'] == null): ?>
                                        <div class="box-dark-element-custom">No subjects</div>
                                    <?php endif; ?>
                                    <?php foreach ($tutor['subjects'] as $subject): ?>
                                        <div class="box-dark-element-custom"><?=$subject['NAME']?></div>
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
        </div>
    </div>
</div>

