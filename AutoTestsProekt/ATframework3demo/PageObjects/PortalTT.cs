using atFrameWork2.BaseFramework;
using atFrameWork2.SeleniumFramework;
using atFrameWork2.BaseFramework.LogTools;
using ATframework3demo.PageObjects;
using OpenQA.Selenium;
using System.Diagnostics.Metrics;
using System.Numerics;
using System.Security.Cryptography.X509Certificates;
using System.Threading.Tasks;
using atFrameWork2.TestEntities;
using System;

namespace atFrameWork2.PageObjects
{
    public class PortalTT
    {
        public HeaderTT openHeaderLogo()
        {
            var tabHeaderLogo = new WebItem("//a[@href='/overview/']", "Перейти на главную страницу");
            if (!tabHeaderLogo.WaitElementDisplayed(3))
                Log.Error("Не найдено: Кнопка Перейти на главную страницу");
            else
                tabHeaderLogo.Click();
            return new HeaderTT();
        }
        public LoginPage openHeaderLogin()
        {
            var tabHeaderLogin = new WebItem("//a[@id='myProfileButton']", "Перейти в свой профиль (логин)");
            if (!tabHeaderLogin.WaitElementDisplayed(3))
                Log.Error("Не найдено: Перейти в свой профиль (логин)");
            else
                tabHeaderLogin.Click();
            return new LoginPage();
        }
        public ProfilePage enterSetting()
        {
            var tabProfSettings = new WebItem("//a[@id='settingsprof']", "Открыть настройки профиля");
            if (!tabProfSettings.WaitElementDisplayed(3))
                Log.Error("Не найдено: Открыть настройки профиля");
            else
                tabProfSettings.Click();
            return new ProfilePage();
        }
        public ProfilePage enterLogout()
        {
            var tabLogout = new WebItem("//a[@href='/overview/']", "Кнопка logout");
            if (!tabLogout.WaitElementDisplayed(3))
                Log.Error("Не найдено: Кнопка logout");
            else
                tabLogout.Click();
            return new ProfilePage();
        }
        public RegistrationPage openHeaderReg()
        {
            var tabHeaderReg = new WebItem("//div[@id='navbarNav']//a[@href='/registration/']", "Перейти к регистрации"); ;
            if (!tabHeaderReg.WaitElementDisplayed(3))
                Log.Error("Не найдено: Кнопка Перейти к регистрации");
            else
                tabHeaderReg.Click();
            return new RegistrationPage();
        }
        public OverviewPage openOverview()
        {
            var tabHeaderLogo = new WebItem("//a[@href='/overview/']", "Перейти на главную страницу");
            if (!tabHeaderLogo.WaitElementDisplayed(3))
                Log.Error("Не найдено: Кнопка Перейти на главную страницу");
            else
                tabHeaderLogo.Click();
            return new OverviewPage();
        }
    }
}