package com.skcraft.launcher.util;

/**
 *  This class is used to replace https://launchermeta.mojang.com/ and https://launcher.mojang.com/ with https://bmclapi2.bangbang93.com
 */
public class BmclSourceUtil {
    public static String replaceMojangUrlToBmclUrl(String content) {
        String result = content.replace("https://launchermeta.mojang.com/", "https://bmclapi2.bangbang93.com/");
        result = result.replace("https://launcher.mojang.com/", "https://bmclapi2.bangbang93.com/");
        return result;
    }
}
