using atFrameWork2.BaseFramework.LogTools;
using atFrameWork2.PageObjects;
using atFrameWork2.SeleniumFramework;
using atFrameWork2.TestEntities;
using ATframework3demo.BaseFramework;
using OpenQA.Selenium;
using System;
using System.Collections.Generic;
using System.Text;

namespace atFrameWork2.BaseFramework
{
    public class TestCase
    {
        public static TestCase RunningTestCase { get; set; }

        /// <summary>
        /// 
        /// </summary>
        /// <param name="title">Название тесткейса</param>
        /// <param name="body">Ссылка на метод тела кейса</param>
        /// <exception cref="ArgumentNullException"></exception>
        public TestCase(string title, Action<TutorPortalHomePage> body)
        {
            Title = title ?? throw new ArgumentNullException(nameof(title));
            Body = body ?? throw new ArgumentNullException(nameof(body));
            Node = new TestCaseTreeNode(title);
        }

        int logCounter = 0;

        public void Execute(PortalInfo testPortal, Action uiRefresher)
        {
            Status = TestCaseStatus.running;
            uiRefresher.Invoke();
            RunningTestCase = this;
            logCounter++;
            CaseLogPath = Path.Combine(Environment.CurrentDirectory, $"caselog{DateTime.Now:ddMMyyyyHHmmss}{logCounter}.html");
            Log.WriteHtmlHeader(CaseLogPath);
            uiRefresher.Invoke();

            try
            {
                Log.Info($"---------------Запуск кейса '{Title}'---------------");
                var portalPage = new TutorPortal(testPortal);
                var homePage = portalPage.OpenHome();
                Body.Invoke(homePage);
            }
            catch (Exception e)
            {
                Log.Error($"Кейс не пройден, причина:{Environment.NewLine}{e}");
            }

            Log.Info($"---------------Кейс '{Title}' завершён---------------");

            try
            {
                if (WebItem._defaultDriver != default)
                {
                    WebItem.DefaultDriver.Quit();
                    WebItem.DefaultDriver = default;
                }
            }
            catch (Exception) { }

            if(CaseLog.Any(x => x is LogMessageError))
                Status = TestCaseStatus.failed;
            else
                Status = TestCaseStatus.passed;

            RunningTestCase = default;
            uiRefresher.Invoke();
        }

        public string Title { get; set; }
        Action<TutorPortalHomePage> Body { get; set; }
        public TestCaseTreeNode Node { get; set; }
        public string CaseLogPath { get; set; }
        public List<LogMessage> CaseLog { get; } = new List<LogMessage>();
        public TestCaseStatus Status { get; set; }
    }
}
