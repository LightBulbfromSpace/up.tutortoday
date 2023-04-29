<?php

use Bitrix\Main\Routing\Controllers\PublicPageController;
use Bitrix\Main\Routing\RoutingConfigurator;
use Up\Tutortoday\Controller\AuthController;
use Up\Tutortoday\Controller\MainPageController;
use Up\Tutortoday\Controller\ProfileController;
use Up\Tutortoday\Services\EducationService;

return function (RoutingConfigurator $routes) {
    $routes->get('/', new PublicPageController('/local/view/tutortoday/tutortoday-main.php'));
    $routes->get('/login/', new PublicPageController('/local/view/tutortoday/tutortoday-login.php'));
    $routes->get('/registration/', new PublicPageController('/local/view/tutortoday/tutortoday-registration.php'));
    $routes->get('/profile/{id}/', new PublicPageController('/local/view/tutortoday/tutortoday-profile.php'));
    $routes->get('/profile/{id}/settings/', new PublicPageController('/local/view/tutortoday/tutortoday-profile-settings.php'));


    $routes->get('/profile/settings/allSubjects/', function () {
        return ProfileController::getAllSubjectsJSON();
    });

    $routes->get('/logout/', function (){
        AuthController::LogoutAction();
    });

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

    $routes->post('/profile/settings/deleteSubject/', function () {
        ProfileController::deleteSubject(getPostList());
    });
    $routes->post('/profile/settings/addTime/', function (){
        ProfileController::createTime(getPostList());
    });
    $routes->post('/profile/settings/deleteTime/', function (){
        ProfileController::deleteTime(getPostList());
    });
    $routes->post('/profile/{id}/delete/', function ($id) {
        (new ProfileController((int)$id))->deleteProfile();
    });
    $routes->post('/profile/{id}/settings/changePassword/', function ($id) {
        return (new ProfileController((int)$id))->updatePassword(getPostList());
    });
};