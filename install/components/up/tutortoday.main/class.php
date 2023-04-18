<?php

use Bitrix\Main\Localization\Loc;
use Up\Tutortoday\Controller\MainPageController;
use Up\Tutortoday\Services\ErrorService;

Loc::loadMessages(__FILE__);

class TutorTodayMainPageComponent extends CBitrixComponent {
    public function executeComponent()
    {
        $this->prepareLocalization();
        $this->onPrepareComponentParams($this->arParams);
        //TODO: add filters
        $this->fetchTutors($this->arResult['page']);
        $this->prepareTemplateParams($this->arParams);
        $this->includeComponentTemplate();
    }

    public function prepareTemplateParams($arParams)
    {

    }

    public function onPrepareComponentParams($arParams)
    {
        $this->arResult['page'] = (int)$arParams['page'];

        return $arParams;
    }

    protected function prepareLocalization()
    {
    }

    protected function fetchTutors(int $page, array $filters = [])
    {
        $maxPage = MainPageController::getNumberOfPages();
        $page = $page === 0 ? 1 : $page;
        if ($page < 1 || $page > $maxPage)
        {
            $this->arResult['tutors'] = [];
            return;
        }

        $this->arResult['tutors'] = MainPageController::getTutorsByPage($page, $filters);
    }
}