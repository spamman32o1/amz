<?php
function admin_config(bool $forceReload = false): array
{
    static $config;
    if ($forceReload || $config === null) {
        $config = include __DIR__ . '/../config.php';
    }
    return $config;
}

function admin_config_file(): string
{
    return __DIR__ . '/../config.json';
}

function admin_load_raw_config(): array
{
    $file = admin_config_file();
    if (!file_exists($file)) {
        return [
            'telegram' => [
                'enabled' => false,
                'chat_id' => '',
                'bot_url' => '',
            ],
            'admin' => [
                'username' => '',
                'password' => '',
            ],
            'afk' => [
                'enabled' => true,
            ],
        ];
    }

    $contents = file_get_contents($file);
    if ($contents === false) {
        throw new RuntimeException('Unable to read configuration file.');
    }

    $data = json_decode($contents, true);
    if (!is_array($data)) {
        $data = [];
    }

    if (!isset($data['afk']) || !is_array($data['afk'])) {
        $data['afk'] = [];
    }
    if (!array_key_exists('enabled', $data['afk'])) {
        $data['afk']['enabled'] = true;
    }
    $data['afk']['enabled'] = (bool)$data['afk']['enabled'];

    return $data;
}

function admin_save_raw_config(array $config): void
{
    $file = admin_config_file();
    $config['afk']['enabled'] = !empty($config['afk']['enabled']);

    $encoded = json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($encoded === false) {
        throw new RuntimeException('Unable to encode configuration JSON.');
    }

    $encoded .= PHP_EOL;

    if (file_put_contents($file, $encoded, LOCK_EX) === false) {
        throw new RuntimeException('Unable to persist configuration file.');
    }
}

function admin_set_afk_enabled(bool $enabled): void
{
    $config = admin_load_raw_config();
    $config['afk']['enabled'] = $enabled;
    admin_save_raw_config($config);
    admin_config(true);
}

function admin_afk_enabled(): bool
{
    $config = admin_config();
    return !empty($config['afk']['enabled']);
}

function admin_csrf_token(bool $regenerate = false): string
{
    if ($regenerate || empty($_SESSION['admin_csrf_token'])) {
        $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['admin_csrf_token'];
}

function admin_verify_csrf_token(string $token): bool
{
    if (empty($token) || empty($_SESSION['admin_csrf_token'])) {
        return false;
    }

    return hash_equals($_SESSION['admin_csrf_token'], $token);
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
