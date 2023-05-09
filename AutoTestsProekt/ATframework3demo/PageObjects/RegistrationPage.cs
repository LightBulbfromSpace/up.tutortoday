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

namespace atFrameWork2.PageObjects
{
    public class RegistrationPage
    {
        public RegistrationPage enterRegTutorName(string Name)
        {
            var inputName = new WebItem("//input[@name='name']", "Поле ввода имени");
            inputName.SendKeys(Name);
            return this;
        }
        public RegistrationPage enterRegTutorSurname(string Surname)
        {
            var inputSurname = new WebItem("//input[@name='lastName']", "Поле ввода фамилии");
            inputSurname.SendKeys(Surname);
            return this;
        }
        public RegistrationPage enterRegTutorMiddleName(string MiddleName)
        {
            var inputMiddleName = new WebItem("//input[@name='middleName']", "Поле ввода отчества");
            inputMiddleName.SendKeys(MiddleName);
            return this;
        }
        public RegistrationPage enterRegTutorLogin(string Login)
        {
            var inputLogin = new WebItem("//input[@name='login']", "Поле ввода логина");
            inputLogin.SendKeys(Login);
            return this;
        }
        public RegistrationPage enterRegTutorPassword(string Password)
        {
            var inputPassword = new WebItem("//input[@name='password']", "Поле ввода пароля");
            inputPassword.SendKeys(Password);
            return this;
        }
        public RegistrationPage enterRegTutorConfirmPassword(string ConfirmPassword)
        {
            var inputConfirmPassword = new WebItem("//input[@name='confirmPassword']", "Поле подтверждения пароля");
            inputConfirmPassword.SendKeys(ConfirmPassword);
            return this;
        }
        public RegistrationPage enterRegTutorEmail(string Email)
        {
            var inputEmail = new WebItem("//input[@name='email']", "Поле ввода Email");
            inputEmail.SendKeys(Email);
            return this;
        }
        public RegistrationPage enterRegTutorWorkingEmail(string WorkingEmail)
        {
            var inputWorkingEmail = new WebItem("//input[@name='workingEmail']", "Поле ввода Working Email");
            inputWorkingEmail.SendKeys(WorkingEmail);
            return this;
        }
        public RegistrationPage enterRegTutorWorkingPhone(string WorkingPhone)
        {
            var inputWorkingPhone = new WebItem("//input[@name='phoneNumber']", "Поле ввода Working Phone");
            inputWorkingPhone.SendKeys(WorkingPhone);
            return this;
        }
        public RegistrationPage enterRegTutorProfileDescription(string ProfileDescription)
        {
            var inputProfileDescription = new WebItem("//textarea[@name='description']", "Поле ввода Profile Description");
            inputProfileDescription.SendKeys(ProfileDescription);
            return this;
        }
        public RegistrationPage chooseCity(string value)
        {
            var selectCity = new WebItem("//select[@name='city']", "Выбор города");
            if (selectCity.WaitElementDisplayed(10))
            {
                selectCity.SelectListItemByText(value);
            }
            else Log.Error("Не найдено: Выбор города");
            return this;
        }
        public RegistrationPage chooseRole(string value) //для кейса с студентом
        {
            var selectRole = new WebItem("//select[@name='role']", "Выбор роли аккаунта");
            if (selectRole.WaitElementDisplayed(10))
            {
                selectRole.SelectListItemByText(value);
            }
            else Log.Error("Не найдено: Выбор роли аккаунта");
            return this;
        }
        public RegistrationPage showSubjects()
        {
            var btnShowSubjects = new WebItem("//button[@id='select-subjects-button']", "Показать предметы");
            if (btnShowSubjects.WaitElementDisplayed(10))
            {
                btnShowSubjects.Click();
            }
            else Log.Error("Не найдено: Показать предметы");
            return this;
        }
        public RegistrationPage selectSubject(string SubjectValue)
        {
            var inputSubjectValue = new WebItem("//input[@name='subjects[]'][@value='"+ SubjectValue + "']", "Выбор предмета");
            if (inputSubjectValue.WaitElementDisplayed(10))
            {
                inputSubjectValue.Click();
            }
            else Log.Error("Не найдено: Выбор предмета");
            return this;
        }
        public RegistrationPage saveSubject()
        {
            var btnSaveSubjects = new WebItem("//button[@id='select-subjects-save-button']", "Сохранить предмет");
            if (btnSaveSubjects.WaitElementDisplayed(10))
            {
                btnSaveSubjects.Click();
            }
            else Log.Error("Не найдено: Сохранить предмет");
            return this;
        }
        public RegistrationPage showEdFormats()
        {
            var showEdFormats = new WebItem("//button[@id='select-ed-formats-button']", "Показать формат обучения");
            showEdFormats.Click();
            return this;
        }
        public RegistrationPage selectEdFormat(string SubjectValue)
        {
            var inputselectEdFormats = new WebItem("//input[@name='edFormats[]'][@value='" + SubjectValue + "']", "Выбор формата обучения");
            if (inputselectEdFormats.WaitElementDisplayed(10))
            {
                inputselectEdFormats.Click();
            }
            else Log.Error("Не найдено: Выбор формата обучения");
            return this;
        }
        public RegistrationPage saveEdFormat()
        {
            var btnSaveEdFormat = new WebItem("//button[@id='select-ed-formats-save-button']", "Сохранить формать обучения");
            if (btnSaveEdFormat.WaitElementDisplayed(10))
            {
                btnSaveEdFormat.Click();
            }
            else Log.Error("Не найдено: Сохранить формать обучения");
            return this;
        }
        public RegistrationPage enterRegisterMe()
        {
            var btnRegisterMe = new WebItem("//button[@id='confirm-registration-button']", "Кнопка Register Me");
            if (btnRegisterMe.WaitElementDisplayed(10))
            {
                btnRegisterMe.Click();
            }
            else Log.Error("Не найдено: Кнопка Register Me");
            return this;
        }
        public RegistrationPage enterAllFormDate(string randID) // сделал пакет с пакетами из Пятёрочки
        {
            //заполнение окон регистрации
            string assertRegTutorName = randID;
            string assertRegTutorSurname = "Vasch-" + randID;
            string assertRegTutorMiddleName = "Serg-" + randID;
            string assertRegTutorLogin = "Vasch-" + randID;
            string assertRegTutorPassword = "vaschanya";
            string assertRegTutorConfirmPassword = "vaschanya";
            string assertRegTutorEmail = "mail@mail.ru";
            string assertRegTutorWorkingEmail = "mail@mail.ru";
            string assertRegTutorWorkingPhone = "+79102425666";
            string assertRegTutorProfileDescription = "RazDvaTri-" + randID;
            string assertRegTutorCity = "Saint-Petersburg"; // СПИСОК ГОРОДОВ В БД: Moscow, Saint-Petersburg, Kaliningrad, Penza, Saratov, Volgograd, Rostov-na-Donu, Vladivostok, Kazan,  Kostroma, Saransk, Samara
            string assertRegTutorSubject = "1"; // СПИСКО ПРЕДМЕТОВ В БД: "1" - mathematics,"2" - english language,"3" - german language,"4" - french language,"5" - spanish language,"6" - russian language,"7" - literature,"8" - informatics,"9" - history, "10" - social science,"11" - physics,"12" - astronomy,"13" - chemistry,"14" - geography.
            string assertRegTutorEdFormat = "2"; // ЗНАЧЕНИЯ ФОРМАТОВ ОБУЧЕНИЯ: "1" - online, "2" - offline

            RegTutorTT myRegTutor = new RegTutorTT(assertRegTutorName, assertRegTutorSurname, assertRegTutorMiddleName, assertRegTutorLogin, assertRegTutorPassword, assertRegTutorConfirmPassword, assertRegTutorEmail, assertRegTutorWorkingEmail, assertRegTutorWorkingPhone, assertRegTutorProfileDescription, assertRegTutorCity, assertRegTutorSubject, assertRegTutorEdFormat);
            this.enterRegTutorName(myRegTutor.Name)
                .enterRegTutorSurname(myRegTutor.Surname)
                .enterRegTutorMiddleName(myRegTutor.MiddleName)
                .enterRegTutorLogin(myRegTutor.Login)
                .enterRegTutorPassword(myRegTutor.Password)
                .enterRegTutorConfirmPassword(myRegTutor.ConfirmPassword)
                .enterRegTutorEmail(myRegTutor.Email)
                .enterRegTutorWorkingEmail(myRegTutor.WorkingEmail)
                .enterRegTutorWorkingPhone(myRegTutor.WorkingPhone)
                .enterRegTutorProfileDescription(myRegTutor.ProfileDescription)
                .chooseCity(myRegTutor.City) //выбор города                            
                .showSubjects() //выбор предмета обучения
                .selectSubject(myRegTutor.Subject)
                .saveSubject()
                //выбор формата обучения
                .showEdFormats()
                .selectEdFormat(myRegTutor.EdFormat)
                .saveEdFormat();
            return this;
        }
        public bool tutorIsAdd(string randID) // проверка создания профиля репетитора
        {
            var tutorArea = new WebItem("//h2[@class='card-title-custom']",
                "Область списка репетиторов");
            if (!tutorArea.WaitElementDisplayed(10))
            {
                RegTutorTT.StopRegTutor("Профиль репетитора не зарегистрирован");
            }
            bool tutorIsAdd = Waiters.WaitForCondition(() => tutorArea.AssertTextContains(randID, default), 2, 6,
                $"Ожидание появления строки '{randID}' в '{tutorArea.Description}'");
            return tutorIsAdd;
        }
        public bool studentIsAdd(string randID) // проверка создания профиля студента
        {
            var studentArea = new WebItem("//div[@class='box-dark-element-custom']",
                "Область ФИО в профиле");
            if (!studentArea.WaitElementDisplayed(10))
            {
                RegTutorTT.StopRegTutor("Профиль студента не зарегистрирован");
            }
            bool studentIsAdd = Waiters.WaitForCondition(() => studentArea.AssertTextContains(randID, default), 2, 6,
                $"Ожидание появления строки '{randID}' в '{studentArea.Description}'");
            return studentIsAdd;
        }
    }
}