<?php

use Bitrix\Main\Routing\Controllers\PublicPageController;
use Bitrix\Main\Routing\RoutingConfigurator;
use Up\Tutortoday\Controller\AdminController;
use Up\Tutortoday\Controller\AuthController;
use Up\Tutortoday\Controller\MainPageController;
use Up\Tutortoday\Controller\ProfileController;
use Up\Tutortoday\Services\EducationService;
use Up\Tutortoday\Services\ErrorService;

return function (RoutingConfigurator $routes) {
    $routes->get('/', new PublicPageController('/local/view/tutortoday/tutortoday-main.php'));
    $routes->get('/overview/', new PublicPageController('/local/view/tutortoday/tutortoday-overview.php'));
    $routes->get('/about/', new PublicPageController('/local/view/tutortoday/tutortoday-about.php'));
    $routes->get('/login/', new PublicPageController('/local/view/tutortoday/tutortoday-login.php'));
    $routes->get('/registration/', new PublicPageController('/local/view/tutortoday/tutortoday-registration.php'));
    $routes->get('/profile/{id}/', new PublicPageController('/local/view/tutortoday/tutortoday-profile.php'));
    $routes->get('/profile/{id}/settings/', new PublicPageController('/local/view/tutortoday/tutortoday-profile-settings.php'));
    $routes->get('/admin/', new PublicPageController('/local/view/tutortoday/tutortoday-admin.php'));

    $routes->get('/admin/users/', function () {
        global $USER;
        return (new AdminController((int)$USER->GetID()))->getUsers(getGetList());
    });
    $routes->get('/admin/subjects/', function () {
        global $USER;
        return (new AdminController((int)$USER->GetID()))->getSubjects(getGetList());
    });
    $routes->get('/admin/edFormats/', function () {
        global $USER;
        return (new AdminController((int)$USER->GetID()))->getEdFormats(getGetList());
    });
    $routes->get('/admin/cities/', function () {
        global $USER;
        return (new AdminController((int)$USER->GetID()))->getCities(getGetList());
    });

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
        global $USER;
        return json_encode((new ProfileController((int)$USER->getID()))->getUserTimeByDayID(getPostList()));
    });

    $routes->post('/profile/{id}/settings/', function () {
        global $USER;
        (new ProfileController((int)$USER->GetID()))->updateUser();
    });

    $routes->post('/profile/settings/deleteSubject/', function () {
        global $USER;
        (new ProfileController((int)$USER->GetID()))->deleteSubject(getPostList());
    });
    $routes->post('/profile/settings/addTime/', function (){
        global $USER;
        return json_encode((new ProfileController((int)$USER->getID()))->createTime(getPostList()));
    });
    $routes->post('/profile/settings/deleteTime/', function (){
        ProfileController::deleteTime(getPostList());
    });
    $routes->post('/profile/delete/', function () {
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

    $routes->post('/admin/user/block/', function () {
        global $USER;
        return json_encode((new AdminController((int)$USER->GetID()))->setUserBlockInfo(getPostList()));
    });

    $routes->post('/admin/add/subjects/', function () {
        global $USER;
        return (new AdminController((int)$USER->GetID()))->addSubject(getPostList());
    });
    $routes->post('/admin/add/edFormat/', function () {
        global $USER;
        return (new AdminController((int)$USER->GetID()))->addEdFormat(getPostList());
    });
    $routes->post('/admin/add/city/', function () {
        global $USER;
        return (new AdminController((int)$USER->GetID()))->addCity(getPostList());
    });
    $routes->post('/admin/edit/subject/', function () {
        global $USER;
        return (new AdminController((int)$USER->GetID()))->editSubject(getPostList());
    });
    $routes->post('/admin/edit/edFormat/', function () {
        global $USER;
        return (new AdminController((int)$USER->GetID()))->editEdFormat(getPostList());
    });
    $routes->post('/admin/edit/city/', function () {
        global $USER;
        return (new AdminController((int)$USER->GetID()))->editCity(getPostList());
    });
    $routes->post('/admin/delete/subject/', function () {
        global $USER;
        return (new AdminController((int)$USER->GetID()))->deleteSubject(getPostList());
    });
    $routes->post('/admin/delete/edFormat/', function () {
        global $USER;
        return (new AdminController((int)$USER->GetID()))->deleteEdFormat(getPostList());
    });
    $routes->post('/admin/delete/city/', function () {
        global $USER;
        return (new AdminController((int)$USER->GetID()))->deleteCity(getPostList());
    });
};