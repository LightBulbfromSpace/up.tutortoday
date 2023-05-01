<?php

use Bitrix\Main\Localization\Loc;
use Up\Tutortoday\Controller\MainPageController;
use Up\Tutortoday\Controller\ProfileController;
use Up\Tutortoday\Services\DatetimeService;
use Up\Tutortoday\Services\ErrorService;
use Up\Tutortoday\Services\UserService;

Loc::loadMessages(__FILE__);

class TutorTodayProfileComponent extends CBitrixComponent {
    public function executeComponent()
    {
        $this->prepareLocalization();
        $this->onPrepareComponentParams($this->arParams);
        $this->fetchUserInfo($this->arResult['ID']);
    //    $this->fetchLoggedInUserInfo();
        $this->fetchWeekdays();
        $this->prepareTemplateParams();
        $this->includeComponentTemplate();
    }

    public function prepareTemplateParams()
    {
        $this->arResult['isOwner'] = (new ProfileController($this->arResult['ID']))->isOwnerOfProfile();
    }

    public function onPrepareComponentParams($arParams)
    {
        $this->arResult['ID'] = (int)$arParams['ID'];

        return $arParams;
    }

    protected function prepareLocalization()
    {
    }

    protected function fetchUserInfo(int $ID)
    {
        global $USER;
        $user = (new UserService($ID))->getUserByID((int)$USER->GetID());
        if ($user === false)
        {
            LocalRedirect('/404/');
        }

        $this->arResult['user'] = $user;
    }

//    protected function fetchLoggedInUserInfo()
//    {
//        global $USER;
//
//        if ($USER->GetID() === null)
//        {
//            $this->arResult['loggedInUser'] = null;
//            return;
//        }
//
//        $user = (new UserService($USER->GetID()))->getUserByID();
//        $this->arResult['loggedInUser'] = $user;
//    }

    protected function fetchWeekdays()
    {
        $this->arResult['weekdays'] = DatetimeService::getAllWeekdays();
    }
}