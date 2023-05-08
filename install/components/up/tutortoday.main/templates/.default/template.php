<?php
/**
 * @var array $arResult
 */

global $USER, $APPLICATION;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

    <main>
       <div class="main__promo">
           <div class="main__promo-wrapper">
               <div class="container">
                <div class="main__content">
                    <div class="main__content-item  main__content-item--title">Tutors online (offline)</div>
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

<footer class="footer__main">
    <div class="container">
        <div class="footer__content">
            <div class="footer__content-row">
                <img src="/local/components/up/tutortoday.main/templates/.default/static/TutorTodayLogo.svg" alt="logo"/>
                <div class="footer__content-address">
                    <span class="footer__content-link">Kaliningrad, WantingTheWeekends St., 15</span>
                </div>
            </div>
            <div class="footer__content-row desktop-only">
                <span class="footer__content-category">Subjects</span>
                <?php foreach ($arResult['subjects'] as $i => $subject): ?>
                    <a class="footer__content-link link" href="/overview/?subjects%5B%5D=<?=$subject['ID']?>"><?=$subject['NAME']?></a>
                    <?php if ($i === 3) { break; }?>
                <?php endforeach; ?>
                <div class="footer__content">and other</div>
            </div>
            <div class="footer__content-row desktop-only">
                <span class="footer__content-category">Education formats</span>
                <?php foreach ($arResult['edFormats'] as $i => $edFormat): ?>
                    <a class="footer__content-link link" href="/overview/?edFormats%5B%5D=<?=$edFormat['ID']?>"><?=$edFormat['NAME']?></a>
                    <?php if ($i === 3) { break; }?>
                <?php endforeach; ?>
            </div>
            <div class="footer__content-row desktop-only">
                <span class="footer__content-category">Cities</span>
                <?php foreach ($arResult['cities'] as $i => $city): ?>
                    <a class="footer__content-link link" href="/overview/?cities%5B%5D=<?=$city['ID']?>"><?=$city['NAME']?></a>
                    <?php if ($i === 3) { break; }?>
                <?php endforeach; ?>
                <div class="footer__content">and other</div>
            </div>
        </div>
    </div>
</footer>
<script>
    BX.ready(() => {
        let elems = document.getElementsByClassName('tablebodytext')
        if (elems[0]) {
            elems[0].remove()
        }
    })
</script>
</body>
</html>