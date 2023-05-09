using System;
using System.Collections.Generic;
using System.Numerics;
using System.Text;
using atFrameWork2.BaseFramework.LogTools;

namespace atFrameWork2.TestEntities
{
    public class LoginTT
    {
        public string LoginProf { get; set; }
        public string LogPass { get; set; }

        public LoginTT(string _LoginProf, string _LogPass)
        {
            LoginProf = _LoginProf;
            LogPass = _LogPass;
        }
        public static void StopRegStudent(string text)
        {
            Log.Error(text);
            throw new NotImplementedException();
        }
    }
}
