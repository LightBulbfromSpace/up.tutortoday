<?php

use Bitrix\Main\Localization\Loc;
use Up\Tutortoday\Controller\AdminController;
use Up\Tutortoday\Services\ErrorService;

Loc::loadMessages(__FILE__);

class TutorTodayAdminPanelComponent extends CBitrixComponent {
    public function executeComponent()
    {
        global $USER;
        if (!(new AdminController((int)$USER->GetID()))->isAdmin())
        {
            LocalRedirect("/");
        }
        $this->prepareLocalization();
        $this->prepareTemplateParams($this->arParams);
        $this->includeComponentTemplate();
    }

    protected function prepareTemplateParams($arParams)
    {
    }

    protected function prepareLocalization()
    {
    }
}