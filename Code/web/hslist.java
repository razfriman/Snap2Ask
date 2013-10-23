/**
 * @(#)subjects.java
 *
 *
 * @author 
 * @version 1.00 2013/10/22
 */
import java.lang.*;
import java.io.*;
import java.util.*;
import java.net.*; 
public class hslist {
    
    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) 
    {
        	try{
        		ArrayList<String>list = new ArrayList();
        	String url = "file:///C:/Users/user/Documents/javafiles/hslist.txt";
       		String fileStr = "";
            URL google = new URL(url);
            URLConnection yc = google.openConnection();
            BufferedReader in = new BufferedReader(new InputStreamReader(yc
                    .getInputStream()));
            String inputLine;
            while ((inputLine = in.readLine()) != null) 
            {
                fileStr = inputLine;
                if (inputLine.length() == 1 || inputLine.contains("..."))
                	continue;
                else
                	System.out.println(inputLine + " H.S.");
                
            }
        	
        	
        	
        	
        	
        	
        	
        	
        	
        	
        	
        	
        	
        	}//end try
        	catch (Exception e) 
        {
            e.printStackTrace();
        }
    }
}
