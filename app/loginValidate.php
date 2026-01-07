<?php
declare(strict_types=1);
session_start();

require __DIR__ . '/autoload.php';

$envUsername = $_ENV['Username'] ?? '';
$envApiHash  = $_ENV['API_hash'] ?? '';

if (isset($_POST['username'], $_POST['password'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === $envUsername && password_verify($password, $envApiHash)) {
        $_SESSION['is_admin'] = true;
        header('Location: ' . $config['base_url'] . '/app/admin.php');
        exit;
    } else {
        $_SESSION['login_error'] = 'Invalid username or API key';
        header('Location: ' . $config['base_url'] . '/views/login.php');
        exit;
    }
}

header('Location: ' . $config['base_url'] . '/views/login.php');
exit;
