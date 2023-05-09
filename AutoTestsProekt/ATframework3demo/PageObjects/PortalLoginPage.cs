using atFrameWork2.BaseFramework.LogTools;
using atFrameWork2.SeleniumFramework;
using atFrameWork2.TestEntities;
using OpenQA.Selenium;
using System;
using System.Collections.Generic;
using System.Text;
using System.Threading;

namespace atFrameWork2.PageObjects
{
    class PortalLoginPage
    {
        PortalInfo portalInfo;

        public PortalLoginPage(PortalInfo portal)
        {
            portalInfo = portal;
        }
    }
}
