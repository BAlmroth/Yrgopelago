<?php
declare(strict_types=1);
require __DIR__ . '/../autoload.php';

header('Content-Type: application/json');

if (!isset($_POST['userId']) || $_POST['userId'] === '') {
    echo json_encode(['error' => 'No user name provided']);
    exit;
}

$userId = $_POST['userId'];

// Look up user stays
$stmt = $database->prepare("SELECT stays FROM users WHERE name = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && $user['stays'] > 0) {
    echo json_encode(['loyalty' => true, 'stays' => (int)$user['stays']]);
} else {
    echo json_encode(['loyalty' => false, 'stays' => 0]);
}
