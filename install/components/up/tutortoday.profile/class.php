<?php

use Bitrix\Main\Localization\Loc;
use Up\Tutortoday\Controller\MainPageController;
use Up\Tutortoday\Controller\ProfileController;
use Up\Tutortoday\Services\DatetimeService;
use Up\Tutortoday\Services\ErrorService;
use Up\Tutortoday\Services\UserService;

Loc::loadMessages(__FILE__);

class TutorTodayProfileComponent extends CBitrixComponent {
    public function executeComponent()
    {
        $this->prepareLocalization();
        $this->onPrepareComponentParams($this->arParams);
        //TODO: add filters
        $this->fetchUserInfo($this->arResult['ID']);
        //$this->prepareContactInfo($this->arResult['user']['contacts']);
        $this->fetchWeekdays();
        $this->prepareTemplateParams();
        $this->includeComponentTemplate();
    }

    public function prepareTemplateParams()
    {
        $this->arResult['isOwner'] = ProfileController::isOwnerOfProfile($this->arResult['ID']);
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

//    protected function prepareContactInfo($contacts)
//    {
//        foreach ($contacts as $contact)
//        {
//            if ($contact['EMAIL'] !== '')
//            {
//                $this->arResult['email'][] = $contact['EMAIL'];
//            }
//            if ($contact['PHONE_NUMBER'] !== '')
//            {
//                $this->arResult['phone'][] = $contact['PHONE_NUMBER'];
//            }
//            if ($contact['VK_PROFILE'] !== '')
//            {
//                $this->arResult['vk'][] = $contact['VK_PROFILE'];
//            }
//            if ($contact['TELEGRAM_USERNAME'] !== '')
//            {
//                $this->arResult['telegram'][] = $contact['TELEGRAM_USERNAME'];
//            }
//        }
//    }

    protected function fetchWeekdays()
    {
        $this->arResult['weekdays'] = DatetimeService::getAllWeekdays();
    }
}