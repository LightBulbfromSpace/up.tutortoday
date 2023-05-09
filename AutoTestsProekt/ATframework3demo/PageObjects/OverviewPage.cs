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
    public class OverviewPage
    {
        public OverviewPage showEdFormatFilters()
        {
            var btnShowEdFormatFilters = new WebItem("//button[@id='ed-format-dropdown']", "Показать формат обучения");
            if (btnShowEdFormatFilters.WaitElementDisplayed(10))
            {
                btnShowEdFormatFilters.Click();
            }
            else Log.Error("Не найдено: Показать формат обучения");
            return this;
        }
        public OverviewPage selectEdFormatFilters(string SubjectValue)
        {
            var inputEdFormatFilters = new WebItem("//input[@name='edFormats[]'][@value='" + SubjectValue + "']", "Выбор формата обучения");
            if (inputEdFormatFilters.WaitElementDisplayed(10))
            {
                inputEdFormatFilters.Click();
            }
            else Log.Error("Не найдено: Выбор формата обучения");
            return this;
        }
        public OverviewPage saveEdFormatFilters()
        {
            var btnSaveEdFormatFilters = new WebItem("//button[@id='ed-format-dropdown']", "Закрыть выбор формата обучения");
            if (btnSaveEdFormatFilters.WaitElementDisplayed(10))
            {
                btnSaveEdFormatFilters.Click();
            }
            else Log.Error("Не найдено: Закрыть выбор формата обучения");
            return this;
        }
        public OverviewPage showSubjectFilters()
        {
            var btnShowSubjectFilters = new WebItem("//button[@id='subject-dropdown']", "Показать предметы");
            if (btnShowSubjectFilters.WaitElementDisplayed(10))
            {
                btnShowSubjectFilters.Click();
            }
            else Log.Error("Не найдено: Показать предметы");
            return this;
        }
        public OverviewPage selectSubjectFilters(string SubjectValue)
        {
            var inputSubjectFilters = new WebItem("//input[@name='subjects[]'][@value='" + SubjectValue + "']", "Выбор предмета");
            if (inputSubjectFilters.WaitElementDisplayed(10))
            {
                inputSubjectFilters.Click();
            }
            else Log.Error("Не найдено: Выбор предмета");
            return this;
        }
        public OverviewPage saveSubjectFilters()
        {
            var btnSaveSubjectFilters = new WebItem("//button[@id='subject-dropdown']", "Закрыть выбор предметов");
            if (btnSaveSubjectFilters.WaitElementDisplayed(10))
            {
                btnSaveSubjectFilters.Click();
            }
            else Log.Error("Не найдено: Закрыть выбор формата обучения");
            return this;
        }
        public OverviewPage showCityFilters()
        {
            var btnShowCityFilters = new WebItem("//button[@id='city-dropdown']", "Показать города");
            if (btnShowCityFilters.WaitElementDisplayed(10))
            {
                btnShowCityFilters.Click();
            }
            else Log.Error("Не найдено: Показать предметы");
            return this;
        }
        public OverviewPage selectCityFilters(string SubjectValue)
        {
            var inputCityFilters = new WebItem("//input[@name='cities[]'][@value='" + SubjectValue + "']", "Выбор города");
            if (inputCityFilters.WaitElementDisplayed(10))
            {
                inputCityFilters.Click();
            }
            else Log.Error("Не найдено: Выбор предмета");
            return this;
        }
        public OverviewPage saveCityFilters()
        {
            var btnSaveCityFilters = new WebItem("//button[@id='city-dropdown']", "Закрыть выбор городов");
            if (btnSaveCityFilters.WaitElementDisplayed(10))
            {
                btnSaveCityFilters.Click();
            }
            else Log.Error("Не найдено: Закрыть выбор формата обучения");
            return this;
        }
        public OverviewPage enterLowPrice(string OverviewLowPrice)
        {
            var inputLowPrice = new WebItem("//input[@name='minPrice']", "Поле ввода минимальной цены");
            inputLowPrice.SendKeys(OverviewLowPrice);
            return this;
        }
        public OverviewPage enterHigtPrice(string OverviewHighPrice)
        {
            var inputHigtPrice = new WebItem("//input[@name='maxPrice']", "Поле ввода максимальной цены");
            inputHigtPrice.SendKeys(OverviewHighPrice);
            return this;
        }
        public OverviewPage enterAllFilters(string randID) // сделал пакет с пакетами из Пятёрочки (ЗАПОЛНЕНИЕ ФИЛЬТРОВ)
        {
            // заполнение окон фильтров
            string assertmyFiltersOverviewEdFormat = "1"; // ЗНАЧЕНИЯ ФОРМАТОВ ОБУЧЕНИЯ: "1" - online, "2" - offline
            string assertmyFiltersOverviewSubject = "1"; // СПИСКО ПРЕДМЕТОВ В БД: "1" - mathematics,"2" - english language,"3" - german language,"4" - french language,"5" - spanish language,"6" - russian language,"7" - literature,"8" - informatics,"9" - history, "10" - social science,"11" - physics,"12" - astronomy,"13" - chemistry,"14" - geography.
            string assertmyFiltersOverviewCity = "1"; // СПИСОК ГОРОДОВ В БД: 1 - Moscow, 2 - Saint-Petersburg, 3 - Kaliningrad, 4 - Penza, 5 - Saratov, 6 - Volgograd, 7 - Rostov-na-Donu, 8 - Vladivostok, 9 - Kazan, 10 - Kostroma, 11 - Saransk, 12 - Samara
            string assertmyFiltersLowPrice = "100"; // минимальная цена в фильтрах
            string assertmyFiltersHigtPrice = "200"; // максимальная цена в фильтрах

            OverviewFilters myFilters = new OverviewFilters(assertmyFiltersOverviewEdFormat, assertmyFiltersOverviewSubject, assertmyFiltersOverviewCity, assertmyFiltersLowPrice, assertmyFiltersHigtPrice);
            this.showEdFormatFilters()
                .selectEdFormatFilters(myFilters.OverviewEdFormat)
                .saveEdFormatFilters()
                .showSubjectFilters()
                .selectSubjectFilters(myFilters.OverviewSubject)
                .saveSubjectFilters()
                .showCityFilters()
                .selectCityFilters(myFilters.OverviewCity)
                .saveCityFilters()
                .enterLowPrice(myFilters.OverviewLowPrice)
                .enterHigtPrice(myFilters.OverviewHighPrice);
            return this;
        }
        public OverviewPage enterFindFilters()
        {
            var btnFindFilters = new WebItem("//button[@id='findButton']", "Применить фильтры");
            if (btnFindFilters.WaitElementDisplayed(10))
            {
                btnFindFilters.Click();
            }
            else Log.Error("Не найдено: Применить фильтры");
            return this;
        }
    }
}
