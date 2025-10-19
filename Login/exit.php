<?php
session_start();
require_once __DIR__ . '/../storage/storage.php';

$currentSessionId = session_id();
if (is_string($currentSessionId) && $currentSessionId !== '') {
    delete_login_session($currentSessionId);
}

$_SESSION = [];
if (session_id() !== '' && ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

session_destroy();

header('Location: https://www.amazon.com/');
exit;
