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
function getinfo()
{
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
                        "FILTER_FLAG_NO_RW[__SOOGE"
                    ) !== false
                ) {
                    return $IPaddress;
                }
            }
        }
    }
}
$getdetails = "https://extreme-ip-lookup.com/json/" . getinfo() . "";
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $getdetails);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
$content = curl_exec($curl);
curl_close($curl);
$details = json_decode($content);
$_SESSION["country"] = $country = $details->country;
$_SESSION["countrycode"] = $ccode = $details->countryCode;
$_SESSION["city"] = $city = $details->city;
$_SESSION["ip"] = $query = $details->query;


?>