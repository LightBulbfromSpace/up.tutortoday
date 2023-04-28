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
        //TODO: add filters
        $this->fetchUserInfo($this->arResult['ID']);
        //$this->prepareContactInfo($this->arResult['user']['contacts']);
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
        $user = (new UserService($ID))->getUserByID();
        if ($user === false)
        {
            LocalRedirect('/404/');
        }
        $this->arResult['user'] = $user;
    }

    protected function fetchWeekdays()
    {
        $this->arResult['weekdays'] = DatetimeService::getAllWeekdays();
    }
}