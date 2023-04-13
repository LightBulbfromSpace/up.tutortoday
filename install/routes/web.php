<?php

use Bitrix\Main\Routing\Controllers\PublicPageController;
use Bitrix\Main\Routing\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    //TODO: add view-file
    $routes->get('/', new PublicPageController('/local/view/TutorToday/...'));
    $routes->get('/login/', new PublicPageController('/local/view/TutorToday/tutortoday-login.php'));
    $routes->get('/registration/', new PublicPageController('/local/view/TutorToday/tutortoday-registration.php'));

    $routes->post('/login/', function () {
        // ...
    });
    $routes->post('/registration/', function () {
        // ...
    });
};