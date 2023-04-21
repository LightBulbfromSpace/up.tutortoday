<?php

use Bitrix\Main\Localization\Loc;
use Up\Tutortoday\Controller\MainPageController;
use Up\Tutortoday\Controller\ProfileController;
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
        $this->prepareTemplateParams($this->arResult);
        $this->includeComponentTemplate();
    }

    public function prepareTemplateParams($arParams)
    {
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
        $this->arResult['user'] = UserService::getUserByID($ID);
    }
}