<?php

use Bitrix\Main\Routing\Controllers\PublicPageController;
use Bitrix\Main\Routing\RoutingConfigurator;
use Up\Tutortoday\Controller\AuthController;
use Up\Tutortoday\Controller\MainPageController;
use Up\Tutortoday\Controller\ProfileController;

return function (RoutingConfigurator $routes) {
    $routes->get('/', new PublicPageController('/local/view/tutortoday/tutortoday-main.php'));
    $routes->get('/login/', new PublicPageController('/local/view/tutortoday/tutortoday-login.php'));
    $routes->get('/registration/', new PublicPageController('/local/view/tutortoday/tutortoday-registration.php'));
    $routes->get('/profile/{id}/', new PublicPageController('/local/view/tutortoday/tutortoday-profile.php'));
    $routes->get('/profile/{id}/settings/', new PublicPageController('/local/view/tutortoday/tutortoday-profile-settings.php'));

    $routes->post('/search/', function($name) {
	    MainPageController::getTutorsByName($name);
	});

	$routes->post('/main/filter/', function($post) {
	    MainPageController::getTutorsByFilters($post);
	});

    $routes->get('/logout/', function (){
        AuthController::LogoutAction();
    });
	$routes->get('/filter/', new PublicPageController('/local/view/tutortoday/tutortoday-profile.php'));

    $routes->post('/login/', function () {
        AuthController::LoginAction();
    });
    $routes->post('/registration/', function () {
        AuthController::RegistrationAction();
    });
    $routes->post('/profile/weekday/', function () {
        return ProfileController::getUserTimeByDayID(getPostList());
    });
    $routes->post('/profile/{id}/settings/', function ($id) {
        ProfileController::updateUser($id);
    });

    $routes->post('/profile/settings/{id}/delete_subject/', function () {
        ProfileController::deleteSubject(getPostList());
    });
};