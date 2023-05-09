using System.Drawing;

namespace ATframework3demo.BaseFramework
{
    public class HelperMethods
    {
        public static string GetHexColor(Color messageColor)
        {
            return "#" + messageColor.R.ToString("X2") + messageColor.G.ToString("X2") + messageColor.B.ToString("X2");
        }
    }
}
