using System;
using System.Collections.Generic;
using System.Numerics;
using System.Text;
using atFrameWork2.BaseFramework.LogTools;

namespace atFrameWork2.TestEntities
{
    public class RegTutorTT
    { 
        public string Name { get; set; }
        public string Surname { get; set; }
        public string MiddleName { get; set; }
        public string Login { get; set; }
        public string Password { get; set; }
        public string ConfirmPassword { get; set; }
        public string Email { get; set; }
        public string WorkingEmail { get; set; }
        public string WorkingPhone { get; set; }
        public string ProfileDescription { get; set; }
        public string City { get; set; }
        public string Subject { get; set; }
        public string EdFormat { get; set; }

        public RegTutorTT(string _Name, string _Surname, string _MiddleName, string _Login, string _Password, string _ConfirmPassword, string _Email, string _WorkingEmail, string _WorkingPhone, string _ProfileDescription, string _City, string _Subject, string _EdFormat)
        {
            Name = _Name;
            Surname = _Surname;
            MiddleName = _MiddleName;
            Login = _Login;
            Password = _Password;
            ConfirmPassword = _ConfirmPassword;
            Email = _Email;
            WorkingEmail = _WorkingEmail;
            WorkingPhone = _WorkingPhone;
            City = _City;
            ProfileDescription = _ProfileDescription;
            Subject = _Subject;
            EdFormat = _EdFormat;
        }
        public static void StopRegTutor(string text)
        {
            Log.Error(text);
            throw new NotImplementedException();
        }
    }
}
