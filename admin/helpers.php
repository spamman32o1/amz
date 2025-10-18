<?php
function admin_config(): array
{
    static $config;
    if ($config === null) {
        $config = include __DIR__ . '/../config.php';
    }
    return $config;
}

function admin_is_authenticated(): bool
{
    return !empty($_SESSION['admin_authenticated']);
}

function admin_require_authentication(): void
{
    if (!admin_is_authenticated()) {
        header('Location: index.php');
        exit;
    }
}

function admin_flash(?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['admin_flash'] = $message;
        return null;
    }

    if (!empty($_SESSION['admin_flash'])) {
        $flash = $_SESSION['admin_flash'];
        unset($_SESSION['admin_flash']);
        return $flash;
    }

    return null;
}

function admin_load_sessions(): array
{
    require_once __DIR__ . '/../storage/storage.php';
    return load_login_sessions();
}

function admin_delete_session(string $sessionId): void
{
    require_once __DIR__ . '/../storage/storage.php';
    delete_login_session($sessionId);
}

function admin_filter_sessions(array $sessions, string $term): array
{
    if ($term === '') {
        return $sessions;
    }

    $lower = function_exists('mb_strtolower') ? 'mb_strtolower' : 'strtolower';
    $term = $lower($term);
    $filtered = [];

    foreach ($sessions as $session) {
        $encoded = json_encode($session, JSON_UNESCAPED_UNICODE);
        if ($encoded === false) {
            $encoded = '';
        }
        $haystack = $lower($encoded);
        if (strpos($haystack, $term) !== false) {
            $filtered[] = $session;
        }
    }

    return $filtered;
}

function admin_paginate_sessions(array $sessions, int $page, int $perPage): array
{
    $total = count($sessions);
    $totalPages = max(1, (int)ceil($total / $perPage));
    $page = max(1, min($page, $totalPages));
    $offset = ($page - 1) * $perPage;
    $items = array_slice($sessions, $offset, $perPage);

    return [$items, $totalPages, $page, $total];
}
?>
