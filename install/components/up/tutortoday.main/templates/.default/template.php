<?php
/**
 * @var array $arResult
 */

global $USER, $APPLICATION;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/local/components/up/tutortoday.main/templates/.default/styles/style.css">
    <link rel="stylesheet" href="/local/templates/tutortoday/template_styles.css">
    <title><?php $APPLICATION->ShowTitle(); ?></title>
    <?php $APPLICATION->ShowHead(); ?>

</head>
<body>
<?php $APPLICATION->ShowPanel(); ?>
<header class="header">
    <div class="container header__container">
        <a class="header__mainLink header__links-item link" href="/overview/">TutorToday</a>
        <div class="header__links">
            <a class="header__links-item link" href="/profile/<?=$USER->GetID()?>/">My Profile</a>
            <a class="header__links-item link" href="#">About</a>
            <a class="header__links-item link" href="#">Contacts</a>
            <a class="header__links-item link" href="/registration/">Registration</a>
        </div>
    </div>
</header>

    <main>
       <div class="main__promo">
           <div class="main__promo-wrapper">
               <div class="container">
                <div class="main__content">
                    <h3 class="main__content-item  main__content-item--title">Tutors online (offline)</h3>
                    <div class="main__content-item main__content-item--subtitle">Available over 700 tutors</div>
                    <div class="main__content-item main__content-item--subtitle2">Find an experienced tutor in one day!</div>
                    <div class="main__content-item main__content-item--subtitle3">More than 3000 students have already trusted us.</div>
                    <div class="main__content-item main__content-item--subtitle4">Choose the best tutors based on the reviews of more than 500 students!</div>
                </div>

                   <a class="main__content-button" href="/overview/">Find a tutor</a>
               </div>
           </div>
       </div>
    </main>

<footer class="footer">
    <div class="container">
        <div class="footer__content">
            <div class="footer__content-row">
                <img src="/local/components/up/tutortoday.main/templates/.default/static/TutorTodayLogo.svg" alt="logo"/>
                <div class="footer__content-address">
                    <span class="footer__content-link">Калининград, туда сюда, улица Хотения выходных, дом 15</span>
                </div>
            </div>
            <div class="footer__content-row">
                <span class="footer__content-category">Subjects</span>
                <?php foreach ($arResult['subjects'] as $i => $subject): ?>
                    <a class="footer__content-link link" href="/overview/?subjects%5B%5D=<?=$subject['ID']?>"><?=$subject['NAME']?></a>
                    <?php if ($i === 5) { break; }?>
                <?php endforeach; ?>
                <div class="footer__content">and other</div>
            </div>
            <div class="footer__content-row">
                <span class="footer__content-category">Education formats</span>
                <?php foreach ($arResult['edFormats'] as $i => $edFormat): ?>
                    <a class="footer__content-link link" href="/overview/?edFormats%5B%5D=<?=$edFormat['ID']?>"><?=$edFormat['NAME']?></a>
                    <?php if ($i === 5) { break; }?>
                <?php endforeach; ?>
            </div>
            <div class="footer__content-row">
                <span class="footer__content-category">Cities</span>
                <?php foreach ($arResult['cities'] as $i => $city): ?>
                    <a class="footer__content-link link" href="/overview/?cities%5B%5D=<?=$city['ID']?>"><?=$city['NAME']?></a>
                    <?php if ($i === 5) { break; }?>
                <?php endforeach; ?>
                <div class="footer__content">and other</div>
            </div>
        </div>
    </div>
</footer>
</body>
</html>