<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\ParameterDictionary;
use Up\Tutortoday\Controller\MainPageController;
use Up\Tutortoday\Services\ErrorService;
use Up\Tutortoday\Services\EducationService;

Loc::loadMessages(__FILE__);

class TutorTodayMainPageComponent extends CBitrixComponent {
    public function executeComponent()
    {
        $this->prepareLocalization();
        $this->onPrepareComponentParams($this->arParams);
	    $this->fetchSubjectFilters();
	    $this->fetchEducationFormatsFilters();

        $this->fetchTutors($this->arResult['page'], getGetList());
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

    protected function fetchTutors(int $page, ParameterDictionary $filters = null)
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

	protected function fetchSubjectFilters()
	{
		$this->arResult['subjects'] = EducationService::getAllSubjects();
	}

	protected function fetchEducationFormatsFilters()
	{
		$this->arResult['edFormats'] = EducationService::getAllEdFormats();
	}
}