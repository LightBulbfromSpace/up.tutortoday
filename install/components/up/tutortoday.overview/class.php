<?php

use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\ParameterDictionary;
use Up\Tutortoday\Controller\MainPageController;
use Up\Tutortoday\Services\ErrorService;
use Up\Tutortoday\Services\EducationService;
use Up\Tutortoday\Services\LocationService;

Loc::loadMessages(__FILE__);

class TutorTodayOverviewPageComponent extends CBitrixComponent {
    public function executeComponent()
    {
        $this->prepareLocalization();
        $this->onPrepareComponentParams($this->arParams);
        $this->prepareFilters(getGetList());
	    $this->fetchSubjectFilters();
	    $this->fetchEducationFormatsFilters();
        $this->fetchLoggedInUser();
        $this->fetchTutors(
            $this->arResult['page'],
            $this->arResult['filters'],
            $this->arResult['search'],
            $this->arResult['myPreferences'],
        );
        $this->fetchAllCities();
        $this->prepareTemplateParams();
        $this->includeComponentTemplate();
    }

    public function prepareFilters(ParameterDictionary $dict)
    {
        $filters = [];
        $this->arResult['search'] = '';
        $this->arResult['myPreferences'] = false;

        foreach ($dict as $key => $item)
        {
            if ($key === 'myPreferences' && $item === 'on')
            {
                $this->arResult['myPreferences'] = true;
                break;
            }
            if ($item === '' || $item === 'page')
            {
                continue;
            }
            if ($key === 'search')
            {
                $this->arResult['search'] = $item;
                continue;
            }
            $filters[$key] = $item;
        }
        unset($filters['page']);
        $this->arResult['filters'] = $filters;
    }

    protected function preparePopupParams()
    {

    }

    public function prepareTemplateParams()
    {
        if ($this->arResult['myPreferences'])
        {
            $this->arResult['currentURIParams'] = 'myPreferences=on';
            return;
        }
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

    protected function fetchTutors(
        $page, array $filters = [],
        string $search = '', bool $userPreferences = false
    )
    {
        if (!is_numeric($page) || $page < 1 || $page == null)
        {
            $page = 1;
        }

        global $USER;
        $controller = new MainPageController($page - 1);

        if ($search !== '')
        {
            $this->arResult['tutors'] = $controller->getTutorsByPageBySearch($search);
        }
        elseif ($filters != [])
        {
            $this->arResult['tutors'] = $controller->getTutorsByPageByFilters($filters);
        }
        elseif ($userPreferences && $USER->GetID() !== null)
        {
            $this->arResult['tutors'] = $controller->getTutorsByPageByUserPreferences($USER->GetID());
        }
        else
        {
            $this->arResult['tutors'] = $controller->getTutorsByPage();
        }

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