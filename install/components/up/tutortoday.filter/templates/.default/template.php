<?php
/**
 * @var array $arResult
 */

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
            <form method="post" action="#">
                <div class="col-md-9 mt-3">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="subject-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Выберите формат
                        </button>
                        <div class="dropdown-menu" aria-labelledby="subject-dropdown">
                            <div class="form-group">
	                            <?php foreach ($arResult['Edformats'] as $subject) : ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="<?= $subject['NAME']?>">
                                        <label class="form-check-label" for="<?= $subject['NAME']?>">
				                            <?= $subject['NAME']?>
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
                            Выберите предметы
                        </button>
                        <div class="dropdown-menu" aria-labelledby="subject-dropdown">
                                <div class="form-group">
	                                <?php foreach ($arResult['subjects'] as $subject) : ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="<?= $subject['NAME']?>">
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
                        <label for="price-from">Нижняя цена:</label>
                        <input type="text" class="form-control" id="price-from" placeholder="Введите нижнюю цену">
                    </div>
                    <div class="col">
                        <label for="price-to">Верхняя цена:</label>
                        <input type="text" class="form-control" id="price-to" placeholder="Введите верхнюю цену">
                    </div>
                </div>
                <button type="submit" class="btn mt-6 ml-3 btn-danger">Найти</button>
            </form>
        </div>
        <div class="col-md-9">
            <div class="mt-3">
                <form class="form-inline my-2 my-lg-0 ">
                    <input class="form-control mr-sm-2" type="search" placeholder="Найти репетитора" aria-label="Search">
                    <button class="btn btn-danger my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>

            <p class="mt-3">Отфильтрованные репетиторы</p>
			<?php foreach ($arResult['tutors'] as $tutor) : ?>
                <div class="card mt-2">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <img src="" class="card-img" alt="Tutor photo">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h2 class="card-title"><?= $tutor['SURNAME']?> <?= $tutor['NAME']?> <?= $tutor['MIDDLE_NAME']?></h2>
                                <p class="card-text"><strong>City: </strong> <?= $tutor['CITY']?></p>
                                <p class="card-text"><strong>Description: </strong><?= $tutor['DESCRIPTION']?></p>
                            </div>
                        </div>
                    </div>
                </div>
			<?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Подключение Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>