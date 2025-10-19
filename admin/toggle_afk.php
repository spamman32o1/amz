<?php
session_start();
require_once __DIR__ . '/helpers.php';

admin_require_authentication();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$token = $_POST['csrf_token'] ?? '';
if (!is_string($token) || !admin_verify_csrf_token($token)) {
    admin_flash('Invalid security token. Please try again.');
    header('Location: dashboard.php');
    exit;
}

$enabledValue = $_POST['afk_enabled'] ?? '';
$enabled = $enabledValue === '1' || $enabledValue === 'true' || $enabledValue === 'on';

try {
    admin_set_afk_enabled($enabled);
    admin_flash('AFK mode ' . ($enabled ? 'enabled' : 'disabled') . '.');
    admin_csrf_token(true);
} catch (Throwable $exception) {
    admin_flash('Unable to update AFK setting. Please try again.');
}

header('Location: dashboard.php');
exit;
