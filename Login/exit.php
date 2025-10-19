<?php
session_start();
require_once __DIR__ . '/../storage/storage.php';

// Clean up any stored data for the active session before leaving the flow.
delete_login_session(session_id());

header('Location: https://www.amazon.com');
exit;
