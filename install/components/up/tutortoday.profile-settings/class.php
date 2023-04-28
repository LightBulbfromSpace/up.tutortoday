<?php

use Bitrix\Main\Localization\Loc;
use Up\Tutortoday\Controller\MainPageController;
use Up\Tutortoday\Controller\ProfileController;
use Up\Tutortoday\Services\DatetimeService;
use Up\Tutortoday\Services\EducationService;
use Up\Tutortoday\Services\ErrorService;
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
    }

    protected function fetchWeekdays()
    {
        $this->arResult['weekdays'] = DatetimeService::getAllWeekdays();
    }

    protected function fetchEducationFormats()
    {
        $this->arResult['edFormats'] = EducationService::getAllEdFormats();
    }

    protected function fetchAllSubjects()
    {
        $this->arResult['subjects'] = EducationService::getAllSubjects();
    }
}