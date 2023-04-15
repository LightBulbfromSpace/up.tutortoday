<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class TutorTodayLoginComponent extends CBitrixComponent {
    public function executeComponent()
    {
        $this->prepareLocalization();
        $this->includeComponentTemplate();
    }

    protected function prepareLocalization()
    {
        $this->arResult['LOGIN_TITLE'] = Loc::getMessage('UP_TUTORTODAY_MODULE_LOGIN_TITLE');
        $this->arResult['LOGIN_PLACEHOLDER'] = Loc::getMessage('UP_TUTORTODAY_MODULE_LOGIN_PLACEHOLDER');
        $this->arResult['PASSWORD_PLACEHOLDER'] = Loc::getMessage('UP_TUTORTODAY_MODULE_PASSWORD_PLACEHOLDER');
    }
}