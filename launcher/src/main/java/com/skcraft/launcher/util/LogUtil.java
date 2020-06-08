package com.skcraft.launcher.util;

import java.io.FileWriter;
import java.io.IOException;

public class LogUtil {
    public static void recordLog(String info) {
        try
        {
            String filename= "log.txt";
            FileWriter fw = new FileWriter(filename,true); //the true will append the new data
            fw.write(info + "\n");//appends the string to the file

            fw.close();
        }
        catch(IOException ioe)
        {
            System.err.println("IOException: " + ioe.getMessage());
        }
    }
}
