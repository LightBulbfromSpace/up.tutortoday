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
    public class LoginPage
    {
        public LoginPage enterLog(string LoginProf)
        {
            var inputName = new WebItem("//input[@name='login']", "Поле ввода логина");
            inputName.SendKeys(LoginProf);
            return this;
        }
        public LoginPage enterPasswd(string LogPass)
        {
            var inputPasswd = new WebItem("//input[@name='password']", "Поле ввода пароля");
            inputPasswd.SendKeys(LogPass);
            return this;
        }
        public LoginPage enterLogin()
        {
            var tabLogin = new WebItem("//button[contains(text(), 'Login')]", "Нажать на кнопку логина");
            if (!tabLogin.WaitElementDisplayed(3))
                Log.Error("Не найдено: Нажать на кнопку логина");
            else
                tabLogin.Click();
            return new LoginPage();
        }
    }
}
