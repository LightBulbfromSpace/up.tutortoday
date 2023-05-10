<?php

use Bitrix\Main\Localization\Loc;
use Up\Tutortoday\Controller\ProfileController;
use Up\Tutortoday\Providers\DatetimeProvider;
use Up\Tutortoday\Providers\EdFormatsProvider;
use Up\Tutortoday\Providers\LocationProvider;
use Up\Tutortoday\Providers\SubjectsProvider;
use Up\Tutortoday\Services\EdFormatsService;
use Up\Tutortoday\Services\UserService;

Loc::loadMessages(__FILE__);

class TutorTodayProfileSettingsComponent extends CBitrixComponent {
    public function executeComponent()
    {
        global $USER;
        if ($USER->GetID() != $this->arResult['ID'])
        {
            LocalRedirect("/profile/{$this->arResult['ID']}/");
        }

        $this->prepareLocalization();
        $this->onPrepareComponentParams($this->arParams);
        $this->fetchUserInfo($this->arResult['ID']);
        $this->fetchWeekdays();
        $this->fetchEducationFormats();
        $this->fetchAllSubjects();
        $this->fetchAllCities();
        $this->prepareTemplateParams();
        $this->includeComponentTemplate();
    }

    public function prepareTemplateParams()
    {
        $this->arResult['isOwner'] = (new ProfileController($this->arResult['ID']))->isOwnerOfProfile();
        foreach ($this->arResult['user']['edFormats'] as $edFormat)
        {
            $this->arResult['user']['edFormatsIDs'][] = $edFormat['EDUCATION_FORMAT']['ID'];
        }
    }

    public function onPrepareComponentParams($arParams)
    {
        $this->arResult['ID'] = (int)$arParams['ID'];

        return $arParams;
    }

    protected function prepareLocalization()
    {
    }

    protected function fetchUserInfo(int $ID)
    {
        $this->arResult['user'] = (new UserService($ID))->getUserByID();
        if ($this->arResult['user'] == null)
        {
            LocalRedirect('/404/');
        }
    }

    protected function fetchWeekdays()
    {
        $this->arResult['weekdays'] = DatetimeProvider::getAllWeekdays();
    }

    protected function fetchEducationFormats()
    {
        $this->arResult['edFormats'] = EdFormatsProvider::getAllEdFormats();
    }

    protected function fetchAllSubjects()
    {
        $this->arResult['subjects'] = SubjectsProvider::getAllSubjects();
    }

    protected function fetchAllCities()
    {
        $this->arResult['cities'] = LocationProvider::getAllCities();
    }
}