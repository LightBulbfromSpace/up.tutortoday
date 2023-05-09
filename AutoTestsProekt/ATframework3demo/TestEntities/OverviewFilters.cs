using System;
using System.Collections.Generic;
using System.Numerics;
using System.Text;
using atFrameWork2.BaseFramework.LogTools;

namespace atFrameWork2.TestEntities
{
    public class OverviewFilters
    { 
        public string OverviewEdFormat { get; set; }
        public string OverviewSubject { get; set; }
        public string OverviewCity { get; set; }
        public string OverviewLowPrice { get; set; }
        public string OverviewHighPrice { get; set; }
        public OverviewFilters(string _OverviewEdFormat, string _OverviewSubject, string _OverviewCity, string _OverviewLowPrice, string _OverviewHighPrice)
        {
            OverviewEdFormat = _OverviewEdFormat;
            OverviewSubject = _OverviewSubject;
            OverviewCity = _OverviewCity;
            OverviewLowPrice = _OverviewLowPrice;
            OverviewHighPrice = _OverviewHighPrice;
        }
        public static void StopOverviewFilters(string text)
        {
            Log.Error(text);
            throw new NotImplementedException();
        }
    }
}
