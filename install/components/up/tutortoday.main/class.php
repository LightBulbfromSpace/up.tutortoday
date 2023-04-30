<?php

use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\ParameterDictionary;
use Up\Tutortoday\Controller\MainPageController;
use Up\Tutortoday\Services\ErrorService;
use Up\Tutortoday\Services\EducationService;
use Up\Tutortoday\Services\LocationService;

Loc::loadMessages(__FILE__);

class TutorTodayMainPageComponent extends CBitrixComponent {
    public function executeComponent()
    {
        $this->prepareLocalization();
        $this->onPrepareComponentParams($this->arParams);
        $this->prepareFilters(getGetList());
	    $this->fetchSubjectFilters();
	    $this->fetchEducationFormatsFilters();
        $this->fetchLoggedInUser();
        $this->fetchTutors($this->arResult['page'], $this->arResult['filters']);
        $this->fetchAllCities();
        $this->prepareTemplateParams();
        $this->includeComponentTemplate();
    }

    public function prepareFilters(ParameterDictionary $dict)
    {
        $filters = [];
        foreach ($dict as $key => $item)
        {
            if ($item === '')
            {
                continue;
            }
            $filters[$key] = $item;
        }
        unset($filters['page']);
        $this->arResult['filters'] = $filters;
    }


    public function prepareTemplateParams()
    {
        $this->arResult['currentURIParams'] = http_build_query($this->arResult['filters']);
    }

    public function onPrepareComponentParams($arParams)
    {
        $this->arResult['page'] = (int)$arParams['page'];

        return $arParams;
    }

    protected function prepareLocalization()
    {
    }

    protected function fetchTutors($page, array $filters = null)
    {
        if (!is_numeric($page) || $page < 1 || $page == null)
        {
            $page = 1;
        }
        $controller = new MainPageController();
        $this->arResult['tutors'] = $controller->getTutorsByPage($page - 1, $filters);

        $maxPage = (int)ceil($controller->getNumberOfUsers() / USERS_BY_PAGE);
        $this->arResult['maxPage'] = $maxPage !== 0 ? $maxPage : 1;

        $this->arResult['currentPage'] = $page;
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

    protected function fetchAllCities()
    {
        $this->arResult['cities'] = LocationService::getAllCities();
    }
}