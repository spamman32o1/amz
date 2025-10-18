<?php
session_start();
require_once __DIR__ . '/helpers.php';

$config = admin_config();
$error = '';

if (admin_is_authenticated()) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $expectedUser = $config['admin']['username'] ?? '';
    $expectedPass = $config['admin']['password'] ?? '';

    $validUser = hash_equals((string)$expectedUser, (string)$username);
    $validPass = hash_equals((string)$expectedPass, (string)$password);

    if ($validUser && $validPass) {
        session_regenerate_id(true);
        $_SESSION['admin_authenticated'] = true;
        $_SESSION['admin_username'] = $expectedUser;
        header('Location: dashboard.php');
        exit;
    }

    $error = 'Invalid credentials supplied.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f7f7f7;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 360px;
        }
        h1 {
            margin-top: 0;
            text-align: center;
            font-size: 1.5rem;
        }
        .error {
            color: #c0392b;
            margin-bottom: 1rem;
            text-align: center;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 0.75rem;
            border: none;
            border-radius: 4px;
            background-color: #232f3e;
            color: #ffffff;
            font-size: 1rem;
            cursor: pointer;
        }
        button:hover {
            background-color: #1a232f;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Admin Login</h1>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" autocomplete="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" autocomplete="current-password" required>

            <button type="submit">Sign In</button>
        </form>
    </div>
</body>
</html>
