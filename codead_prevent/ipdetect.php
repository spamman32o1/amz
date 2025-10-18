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
$ip = $_SESSION["ip"];
$curl = curl_init();
$client = "client={$ip}";

$url = "https://vpnapi.io/widget";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:102.0) Gecko/20100101 Firefox/102.0",
   "Accept: */*",
   "Accept-Language: en-US,en;q=0.5",
   "Accept-Encoding: gzip, deflate, br",
   "Referer: https://vpnapi.io/",
   "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
   "X-CSRFToken: BO2KXkTOjPghdeDCUJSzJShg1snjbef8GQBFnq9NEWHTED1wYnSOlDPYPvwfD64S",
   "X-Requested-With: XMLHttpRequest",
   "Origin: https://vpnapi.io",
   "DNT: 1",
   "Connection: keep-alive",
   "Cookie: csrftoken=wYeb8LcbyutLBDH3zdp1LHHSkLpoecmwB0N6yRsaTBUn225XDRpgnsfA8OykG4bg",
   "Sec-Fetch-Dest: empty",
   "Sec-Fetch-Mode: cors",
   "Sec-Fetch-Site: same-origin",
   "TE: trailers",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$data = "client={$ip}&ip={$ip}&api_key=gAAAAABja5Ovd-C8T8BDpSTMgBF-rFhg4eda_MRlXVrgrQkPbeyAzSqK6ztC5Z7uGkYJMq2NYlqkoavDI5RaKMqLfqK5UJwCYL2-kwxrhQG7l3SVLhXrzXI%3D";

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($curl);
curl_close($curl);

$detail = json_decode($response, true);
$vpn = $detail["security"]["vpn"];
$proxy = $detail["security"]["proxy"];
$tor = $detail["security"]["tor"];
?>