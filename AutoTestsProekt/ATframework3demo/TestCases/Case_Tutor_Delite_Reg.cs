using atFrameWork2.BaseFramework;
using atFrameWork2.BaseFramework.LogTools;
using atFrameWork2.PageObjects;
using atFrameWork2.SeleniumFramework;
using atFrameWork2.TestEntities;
using ATframework3demo.PageObjects;
using System.Xml.Linq;

namespace ATframework3demo.TestCases
{
    public class Case_Tutor_Delite_Reg : CaseCollectionBuilder
    {
        protected override List<TestCase> GetCases()
        {
            var caseCollection = new List<TestCase>();
            caseCollection.Add(new TestCase("Регистрация и удаление аккаунта", homePage => RegTutor(homePage)));
            return caseCollection;
        }

        private static string StopWithLog(String text)
        {
            Log.Error(text);
            throw new NotImplementedException();
        }

        private static string GetUniquedId()
        {
            var ticks = new DateTime(2016, 1, 1).Ticks;
            var ans = DateTime.Now.Ticks - ticks;
            string uniqueId = ans.ToString("x");
            return uniqueId;
        }

        void RegTutor(TutorPortalHomePage homePage)
        {
            string randID = GetUniquedId();
            string assertLogTutorLogin = "Vasch-" + randID;
            string assertLogTutorLogPass = "vaschanya";
            string assertLogTutorCheckLog = "";

            LoginTT myLogTutor = new LoginTT(assertLogTutorLogin, assertLogTutorLogPass, assertLogTutorCheckLog);

            Log.Info("Начало регистрации репетитора, уникальный номер профиля " + randID);
            PortalTT portalTT = new PortalTT();
            RegistrationPage tutorPage = portalTT
                .openHeaderReg() //откывает окно регистрации
                //заполняет поля регистрации
                .enterAllFormDate(randID)
                .enterRegisterMe(); //регистрация по заполненным данным
            portalTT
                .openHeaderLogo(); //перейти на страницу с списком репетиторов
            bool accountIsAdd = tutorPage
                .tutorIsAdd(randID); //проверка наличия зарегистрированного репетитора в общем списке
            if (accountIsAdd)
            {
                Log.Info("Репетитор добавлен, уникальный номер профиля " + randID);
            }
            else
            {
                RegTutorTT.StopRegTutor("Репетитор не добавлен, уникальный номер профиля " + randID);
            }
            portalTT
                .openHeaderLogin();
            portalTT
                .enterSetting()
                .openDeleteProfile()
                .deleteProfile();
        }
    }
}
