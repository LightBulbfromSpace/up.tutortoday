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
        $this->fetchLoggedInUser();
        $this->fetchTutors((int)$this->arResult['page'], getGetList());
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
        $page = $page == null ? 1 : $page;
        $page--;
        if ($page < 0 || $page > $maxPage - 1)
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

    protected function fetchLoggedInUser()
    {
        global $USER;
        $this->arResult['loggedInUser'] = $USER->GetID();
    }
}