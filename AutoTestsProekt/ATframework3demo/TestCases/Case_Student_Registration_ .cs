using atFrameWork2.BaseFramework;
using atFrameWork2.BaseFramework.LogTools;
using atFrameWork2.PageObjects;
using atFrameWork2.SeleniumFramework;
using atFrameWork2.TestEntities;
using ATframework3demo.PageObjects;
using System.Xml.Linq;

namespace ATframework3demo.TestCases
{
    public class Case_Student_Registration_ : CaseCollectionBuilder
    {
        protected override List<TestCase> GetCases()
        {
            var caseCollection = new List<TestCase>();
            caseCollection.Add(new TestCase("Регистрация cтудента и поиск репетиторов", homePage => RegTutor(homePage)));
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
            string assertRegTTRole = "student"; // 1 - "tutor", 2 - "student".

            RegStudentTT myRegTTRole = new RegStudentTT(assertRegTTRole);

            Log.Info("Начало регистрации студента, уникальный номер профиля " + randID);
            PortalTT portalTT = new PortalTT();
            RegistrationPage tutorPage = portalTT
                .openHeaderReg() //откывает окно регистрации
                //заполняет поля регистрации
                .enterAllFormDate(randID)
                .chooseRole(myRegTTRole.Role)
                .enterRegisterMe(); //регистрация по заполненным данным
            bool studentIsAdd = tutorPage
                .studentIsAdd(randID); //проверка регистрации аккаунта студента
                    if (studentIsAdd)
                        {Log.Info("Студент добавлен, уникальный номер профиля " + randID);
                    }
                    else
                        {RegStudentTT.StopRegStudent("Студент не добавлен, уникальный номер профиля " + randID);
                    }
            portalTT
                .openOverview() //перейти на страницу с списком репетиторов*/
                .enterAllFilters(randID) //заполнение окон фильтра
                .enterFindFilters(); //применение фильтров
        }
    }
}
