<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class TutorTodayRegistrationComponent extends CBitrixComponent {
    public function executeComponent()
    {
        $this->prepareLocalization();
        $this->includeComponentTemplate();
    }

    protected function prepareLocalization()
    {
        $this->arResult['REGISTRATION_TITLE'] = Loc::getMessage('UP_TUTORTODAY_MODULE_REGISTRATION_TITLE');
        $this->arResult['NAME_PLACEHOLDER'] = Loc::getMessage('UP_TUTORTODAY_MODULE_NAME_PLACEHOLDER');
        $this->arResult['SURNAME_PLACEHOLDER'] = Loc::getMessage('UP_TUTORTODAY_MODULE_SURNAME_PLACEHOLDER');
        $this->arResult['MIDDLE_NAME_PLACEHOLDER'] = Loc::getMessage('UP_TUTORTODAY_MODULE_MIDDLE_NAME_PLACEHOLDER');
        $this->arResult['PHONE_PLACEHOLDER'] = Loc::getMessage('UP_TUTORTODAY_MODULE_MIDDLE_PHONE_PLACEHOLDER');
        $this->arResult['EMAIL_PLACEHOLDER'] = Loc::getMessage('UP_TUTORTODAY_MODULE_MIDDLE_EMAIL_PLACEHOLDER');
        $this->arResult['VK_PLACEHOLDER'] = Loc::getMessage('UP_TUTORTODAY_MODULE_MIDDLE_VK_PLACEHOLDER');
        $this->arResult['TELEGRAM_PLACEHOLDER'] = Loc::getMessage('UP_TUTORTODAY_MODULE_MIDDLE_TELEGRAM_PLACEHOLDER');
    }
}
