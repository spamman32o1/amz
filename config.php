<?php

$configFile = __DIR__ . '/config.json';

if (!file_exists($configFile)) {
    throw new RuntimeException('Configuration file not found: ' . $configFile);
}

$configContents = file_get_contents($configFile);
$configData = json_decode($configContents, true);

if (!is_array($configData)) {
    throw new RuntimeException('Unable to decode configuration JSON.');
}

$telegram = isset($configData['telegram']) && is_array($configData['telegram']) ? $configData['telegram'] : [];
$admin = isset($configData['admin']) && is_array($configData['admin']) ? $configData['admin'] : [];

$settings = [
    'telegram' => !empty($telegram['enabled']) ? '1' : '0',
    'chat_id' => $telegram['chat_id'] ?? '',
    'bot_url' => $telegram['bot_url'] ?? '',
    'admin' => [
        'username' => $admin['username'] ?? '',
        'password' => $admin['password'] ?? ''
    ]
];

return $settings;

?>
