using atFrameWork2.BaseFramework;
using atFrameWork2.BaseFramework.LogTools;
using atFrameWork2.PageObjects;
using atFrameWork2.SeleniumFramework;
using atFrameWork2.TestEntities;
using ATframework3demo.PageObjects;
using System.Xml.Linq;

namespace ATframework3demo.TestCases
{
    public class Case_Log_Outlog : CaseCollectionBuilder
    {
        protected override List<TestCase> GetCases()
        {
            var caseCollection = new List<TestCase>();
            caseCollection.Add(new TestCase("Вход в профиль и выход", homePage => LogOut(homePage)));
            return caseCollection;
        }

        private static string StopWithLog(String text)
        {
            Log.Error(text);
            throw new NotImplementedException();
        }

        void LogOut(TutorPortalHomePage homePage)
        {
            string assertLogTutorLogin = "ValetPetrovich";
            string assertLogTutorLogPass = "ValetPetrovich";

            LoginTT myLogTutor = new LoginTT(assertLogTutorLogin, assertLogTutorLogPass);

            Log.Info("Начало регистрации репетитора, уникальный номер профиля ");
            PortalTT portalTT = new PortalTT();
            LoginPage loginPage = portalTT
                .openHeaderLogin() //войти на страницу логина
                .enterLog(myLogTutor.LoginProf)
                .enterPasswd(myLogTutor.LogPass)
                .enterLogin(); //залогиниться
            portalTT
                .enterLogout(); //выход из аккаунта
        }
    }
}
