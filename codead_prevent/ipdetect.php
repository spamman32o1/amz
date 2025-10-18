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
$ip = isset($_SESSION["ip"]) ? $_SESSION["ip"] : (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "");

$vpn = "0";
$proxy = "0";
$tor = "0";
$detail = null;

if (!empty($_SESSION["ip_is_private"]) || empty($ip)) {
    return;
}

$apiToken = getenv('VPNAPI_TOKEN');

if (!empty($apiToken) && !empty($ip)) {
    $url = sprintf('https://vpnapi.io/api/%s?key=%s', urlencode($ip), urlencode($apiToken));
    $curl = curl_init($url);

    if ($curl !== false) {
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if ($response !== false && $response !== null && $response !== '') {
            $decoded = json_decode($response, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $detail = $decoded;
            }
        }
    }
}

if (is_array($detail) && isset($detail['security']) && is_array($detail['security'])) {
    $security = $detail['security'];

    $vpn = !empty($security['vpn']) ? "1" : "0";
    $proxy = !empty($security['proxy']) ? "1" : "0";
    $tor = !empty($security['tor']) ? "1" : "0";
}
?>
