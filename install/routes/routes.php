<?php

use Bitrix\Main\Routing\Controllers\PublicPageController;
use Bitrix\Main\Routing\RoutingConfigurator;
use Up\Tutortoday\Controller\AuthController;
use Up\Tutortoday\Controller\MainPageController;
use Up\Tutortoday\Controller\ProfileController;
use Up\Tutortoday\Services\EducationService;
use Up\Tutortoday\Services\ErrorService;

return function (RoutingConfigurator $routes) {
    $routes->get('/', new PublicPageController('/local/view/tutortoday/tutortoday-main.php'));
    $routes->get('/overview/', new PublicPageController('/local/view/tutortoday/tutortoday-overview.php'));
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
    $routes->post('/profile/{id}/settings/', function () {
        global $USER;
        (new ProfileController((int)$USER->GetID()))->updateUser();
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
    $routes->post('/profile/{id}/delete/', function () {
        global $USER;
        (new ProfileController((int)$USER->GetID()))->deleteProfile();
    });
    $routes->post('/profile/settings/changePassword/', function () {
        global $USER;
        return (new ProfileController((int)$USER->GetID()))->updatePassword(getPostList());
    });

    $routes->post('/profile/settings/updatePhotoPreview/', function () {
        global $USER;
        return (new ProfileController((int)$USER->GetID()))->updatePhotoTmp($_FILES['photo']);
    });

    $routes->post('/profile/settings/updatePhotoConfirm/', function () {
        global $USER;
        return json_encode((new ProfileController((int)$USER->GetID()))->updateProfilePhoto());
    });
    $routes->post('/profile/getID/', function () {
        global $USER;
        return $USER->GetID();
    });
    $routes->post('/profile/settings/getProfilePhoto/', function () {
        global $USER;
        return json_encode((new ProfileController((int)$USER->GetID()))->getProfilePhoto());
    });
    $routes->post('/profile/settings/cancelPhotoUpdate/', function () {
        global $USER;
        return json_encode((new ProfileController((int)$USER->GetID()))->cancelProfilePhotoUpdate());
    });
    $routes->post('/profile/settings/deleteProfilePhoto/', function () {
        global $USER;
        return json_encode((new ProfileController((int)$USER->GetID()))->deleteProfilePhoto());
    });
    $routes->post('/profile/feedbacks/add/', function () {
        global $USER;
        return json_encode((new ProfileController((int)$USER->GetID()))->addFeedback(getPostList()));
    });
    $routes->post('/profile/feedbacks/', function () {
        global $USER;
        return (new ProfileController((int)$USER->GetID()))->getFeedbacks(getPostList());
    });
};