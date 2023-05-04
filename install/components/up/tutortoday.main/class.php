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
	    $this->fetchSubjectFilters();
	    $this->fetchEducationFormatsFilters();
        $this->fetchAllCities();
        $this->prepareProfileLink();
        $this->prepareTemplateParams();
        $this->includeComponentTemplate();
    }

    protected function prepareProfileLink()
    {
        global $USER;

        $linkToProfile = '/login/';
        if ($USER->GetID() != null)
        {
            $linkToProfile = "/profile/{$USER->GetID()}/";
        }

        $this->arResult['linkToProfile'] = $linkToProfile;
    }

    public function prepareTemplateParams()
    {
    }

    protected function prepareLocalization()
    {
    }

	protected function fetchSubjectFilters()
	{
		$this->arResult['subjects'] = EducationService::getAllSubjects();
	}

	protected function fetchEducationFormatsFilters()
	{
		$this->arResult['edFormats'] = EducationService::getAllEdFormats();
	}

    protected function fetchAllCities()
    {
        $this->arResult['cities'] = LocationService::getAllCities();
    }
}