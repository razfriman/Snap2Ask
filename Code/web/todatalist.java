/**
 * @todatalist.java
 *
 *
 * @author Vipul Kohli
 * @version 1.00 2013/10/22
 */
import java.lang.*;
import java.io.*;
import java.util.*;
import java.net.*; 

public class todatalist {
    
    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) 
    {
        	try{
        	//url == some courses file to extract
        	String url = "file:///C:/Users/user/Documents/javafiles/courses2.txt";
       		String fileStr = "";
            URL google = new URL(url);
            URLConnection yc = google.openConnection();
            BufferedReader in = new BufferedReader(new InputStreamReader(yc
                    .getInputStream()));
            String inputLine;
            while ((inputLine = in.readLine()) != null) 
            {
                fileStr = inputLine;
                //String math = " in Science";
                if (fileStr.length() < 1)
                	continue;
                //fileStr += math;
                System.out.println("<option label=\"" + fileStr + "\" value=\"" + fileStr + "\">");
            }
        	
        	
        	
        	
        	
        	
        	
        	
        	
        	
        	
        	
        	
        	
        	}//end try
        	catch (Exception e) 
        {
            e.printStackTrace();
        }
    }
}
