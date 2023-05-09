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
using atFrameWork2.PageObjects;

namespace ATframework3demo.PageObjects
{
    public class ProfilePage
    {
        public ProfilePage enterSetting()
        {
            var tabProfSettings = new WebItem("//a[@id='settingsprof']", "Открыть настройки профиля");
            if (!tabProfSettings.WaitElementDisplayed(3))
                Log.Error("Не найдено: Открыть настройки профиля");
            else
                tabProfSettings.Click();
            return this;
        }
        public ProfilePage openDeleteProfile()
        {
            var tabProfDelete = new WebItem("//button[contains(text(), 'Delete Profile')]", "Открыть удаление профиля");
            if (!tabProfDelete.WaitElementDisplayed(5))
                Log.Error("Не найдено: Открыть удаление профиля");
            else
                tabProfDelete.Click();
            return this;
        }
        public ProfilePage deleteProfile()
        {
            var tabProfDelete = new WebItem("//button[contains(text(), 'Delete')]", "Удалить профиль");
            if (!tabProfDelete.WaitElementDisplayed(5))
                Log.Error("Не найдено: Удалить профиль");
            else
                tabProfDelete.Click();
            return this;
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
    }
}
