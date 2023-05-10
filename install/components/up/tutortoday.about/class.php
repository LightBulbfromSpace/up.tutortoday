<?php

use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\ParameterDictionary;
use Up\Tutortoday\Controller\MainPageController;
use Up\Tutortoday\Providers\EdFormatsProvider;
use Up\Tutortoday\Providers\LocationProvider;
use Up\Tutortoday\Providers\SubjectsProvider;
use Up\Tutortoday\Services\ErrorService;
use Up\Tutortoday\Services\EdFormatsService;

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
		$this->arResult['subjects'] = SubjectsProvider::getAllSubjects();
	}

	protected function fetchEducationFormatsFilters()
	{
		$this->arResult['edFormats'] = EdFormatsProvider::getAllEdFormats();
	}

    protected function fetchAllCities()
    {
        $this->arResult['cities'] = LocationProvider::getAllCities();
    }
}