<?php

use Bitrix\Main\Routing\Controllers\PublicPageController;
use Bitrix\Main\Routing\RoutingConfigurator;
use Up\Tutortoday\Controller\AuthController;
use Up\Tutortoday\Controller\ProfileController;

return function (RoutingConfigurator $routes) {
    $routes->get('/', new PublicPageController('/local/view/tutortoday/tutortoday-main.php'));
    $routes->get('/login/', new PublicPageController('/local/view/tutortoday/tutortoday-login.php'));
    $routes->get('/registration/', new PublicPageController('/local/view/tutortoday/tutortoday-registration.php'));
    $routes->get('/profile/{id}/', new PublicPageController('/local/view/tutortoday/tutortoday-profile.php'));


    $routes->post('/login/', function () {
        AuthController::LoginAction();
    });
    $routes->post('/registration/', function () {
        AuthController::RegistrationAction();
    });
    $routes->post('/profile/weekday/', function () {
        return ProfileController::getUserTimeByDayID(getPostList());
    });
};