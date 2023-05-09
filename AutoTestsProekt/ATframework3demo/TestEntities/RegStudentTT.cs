using System;
using System.Collections.Generic;
using System.Numerics;
using System.Text;
using atFrameWork2.BaseFramework.LogTools;

namespace atFrameWork2.TestEntities
{
    public class RegStudentTT
    { 
        public string Role { get; set; } // выбор при регистрации "студент" или "репетитор"

        public RegStudentTT(string _Role)
        {
            Role = _Role;
        }
        public static void StopRegStudent(string text)
        {
            Log.Error(text);
            throw new NotImplementedException();
        }
    }
}
