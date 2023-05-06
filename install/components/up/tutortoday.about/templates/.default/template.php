<?php
/**
 * @var array $arResult
 */

global $USER, $APPLICATION;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<link rel="stylesheet" href="/local/components/up/tutortoday.about/templates/.default/style.css">
    <main class="main">
       <div class="main__promo">
           <div class="main__promo-wrapper">
               <div class="container">
                   <br><br><br>
                   <div class="content-container">
                       <div class="main__content-item  main__content-item--title">What is TutorToday?</div>
                       <div class="main__content-text">
                           <b>TutorToday</b> is a service that helps students and tutors meet.
                           Private specialists and companies can be found by <b>TutorToday</b>.<br>
                           New students and tutors find each other on our website every day.
                       </div>
                       <div class="main__content-item  main__content-item--subtitle">
                           How can a client choose a specialist?
                       </div>
                       <div class="main__content-text">
                           Enter the parameter important for you in the search bar on the website or use sidebar filters to choose
                           the subject you need,<br> the budget you expect and the convenient format of studying.
                           Tutors offer their services and prices.
                           Choose the right one: by reviews,<br> rating and subject. The search for a specialist is free. And for the work done, you pay the specialist directly in a convenient way.
                       </div>
                        <div class="main__content-item  main__content-item--subtitle">
                            How can a tutor find a client?
                        </div>
                        <div class="main__content-text">
                            During the registration tutors enter their phone number and e-mail, which will be visible for site visitors.
                            Also tutors<br> can choose the subject they are going to teach. This subject can be changed in profile settings later.
                            After completing<br> the lesson, you will receive the money directly from the student, the entire amount will remain to you.
                        </div>
                       <div class="main__content-item  main__content-item--subtitle">
                           Who is responsible for the result?
                       </div>
                       <div class="main__content-text">
                           <div>
                               We do not provide services, but help the tutor and students to meet. It is important for us that the service<br> we create is reliable and secure.
                           </div>
                           <div>
                               What are we doing:
                           </div>
                           <ul>
                               <li>
                                   — we block tutors and clients who violate the rules of the service;
                               </li>
                               <li>
                                   — we help to understand difficult situations. If you have any difficulties while working, please contact<br>
                                   us with <u>mail@tutortoday.ru</u>.
                               </li>
                           </ul>
                           <div>
                               What we recommend to you:
                           </div>
                           <ul>
                               <li>
                                   — check the documents of the person with whom you will work. Write down the details of their<br>
                                   passport and registration;
                               </li>
                               <li>
                                   — if the wrong tutor came to you, whom you chose, refuse his services and inform us about it;
                               </li>
                               <li>
                                   — before starting work, draw up a contract. This way you will make sure that<br> both sides have correctly understood the task and conditions;
                               </li>
                               <li>
                                   — if you transfer money or valuables to the tutor, issue a receipt;
                               </li>
                               <li>
                                   — choose tutors who give a guarantee. The warranty conditions are usually specified in the questionnaire.
                               </li>
                           </ul>
                       </div>
                   </div>
               </div>
           </div>
       </div>
    </main>
<footer class="footer__main">
    <div class="container">
        <div class="footer__content">
            <div class="footer__content-row">
                <img src="/local/components/up/tutortoday.about/templates/.default/static/TutorTodayLogo.svg" alt="logo"/>
                <div class="footer__content-address">
                    <span class="footer__content-link">Kaliningrad, WantingTheWeekends St., 15</span>
                </div>
            </div>
            <div class="footer__content-row">
                <span class="footer__content-category">Subjects</span>
                <?php foreach ($arResult['subjects'] as $i => $subject): ?>
                    <a class="footer__content-link link" href="/overview/?subjects%5B%5D=<?=$subject['ID']?>"><?=$subject['NAME']?></a>
                    <?php if ($i === 3) { break; }?>
                <?php endforeach; ?>
                <div class="footer__content">and other</div>
            </div>
            <div class="footer__content-row">
                <span class="footer__content-category">Education formats</span>
                <?php foreach ($arResult['edFormats'] as $i => $edFormat): ?>
                    <a class="footer__content-link link" href="/overview/?edFormats%5B%5D=<?=$edFormat['ID']?>"><?=$edFormat['NAME']?></a>
                    <?php if ($i === 3) { break; }?>
                <?php endforeach; ?>
            </div>
            <div class="footer__content-row">
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