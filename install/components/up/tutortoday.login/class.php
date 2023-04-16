<?php

use Bitrix\Main\Localization\Loc;
use Up\Tutortoday\Services\ErrorService;

Loc::loadMessages(__FILE__);

class TutorTodayLoginComponent extends CBitrixComponent {
    public function executeComponent()
    {
        $this->prepareLocalization();
        $this->prepareTemplateParams($this->arParams);
        $this->includeComponentTemplate();
    }

    public function prepareTemplateParams($arParams)
    {
        $this->arResult['isErr'] = $arParams['err'] != null;
        if ($this->arResult['isErr'])
        {
            $this->arResult['errText'] = ErrorService::getErrorTextByGetCode($arParams['err']);
        }
    }

    protected function prepareLocalization()
    {
        $this->arResult['LOGIN_TITLE'] = Loc::getMessage('UP_TUTORTODAY_MODULE_LOGIN_TITLE');
        $this->arResult['LOGIN_PLACEHOLDER'] = Loc::getMessage('UP_TUTORTODAY_MODULE_LOGIN_PLACEHOLDER');
        $this->arResult['PASSWORD_PLACEHOLDER'] = Loc::getMessage('UP_TUTORTODAY_MODULE_PASSWORD_PLACEHOLDER');
    }
}