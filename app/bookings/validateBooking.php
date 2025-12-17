<?php
declare(strict_types=1);
require __DIR__ . '/../autoload.php';

$errors = [];

$userId = isset($_POST['userId']) ? trim($_POST['userId']) : '';
$checkIn = isset($_POST['checkIn']) ? trim($_POST['checkIn']) : '';
$checkOut = isset($_POST['checkOut']) ? trim($_POST['checkOut']) : '';
$roomName = isset($_POST['room']) ? $_POST['room'] : '';
$selectedFeatures = isset($_POST['features']) ? $_POST['features'] : [];

//validera
if (!isset($_POST['userId']) || $userId === '') {
    $errors[] = "Please provide your name.";
}
if (!isset($_POST['checkIn']) || $checkIn === '') {
    $errors[] = "Please select a check-in date.";
}
if (!isset($_POST['checkOut']) || $checkOut === '') {
    $errors[] = "Please select a check-out date.";
}

if (isset($_POST['checkIn'], $_POST['checkOut']) && $checkIn !== '' && $checkOut !== '' && $checkOut <= $checkIn) {
    $errors[] = "The check-out date can't be before the check-in date.";
}

$room = null;
$rooms = getRooms($database);
foreach ($rooms as $dbRoom) {
    if ($dbRoom['name'] === $roomName) {
        $room = $dbRoom;
    }
}

$totalPrice = 0;
$featuresList = [];
if ($room) {
    $totalPrice = (int)$room['cost'];

    if (!empty($selectedFeatures)) {
        $placeholders = implode(',', array_fill(0, count($selectedFeatures), '?'));
        $stmt = $database->prepare("SELECT name, cost FROM features WHERE name IN ($placeholders)");
        $stmt->execute($selectedFeatures);
        $featuresFromDb = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($featuresFromDb as $feature) {
            $featuresList[] = ($feature['name']);
            $totalPrice += (int)$feature['cost'];
        }
    }
}

if (!isset($errors)) {
    foreach ($errors as $error) {
        echo ($error) ;
        exit;
    }
}

//review choices
if (empty($errors) && $room) {
    ?>
    <h2>Review Your Booking</h2>
    <p>Name:<?= ($userId) ?></p>
    <p>Room:<?= ($room['name']) ?></p>
    <p>Dates:<?= ($checkIn) ?> - <?= ($checkOut) ?></p>
    <p>Selected Features:<?= !empty($featuresList) ? implode(', ', $featuresList) : 'None' ?></p>
    <p>Total Price:<?= $totalPrice ?> Gold</p>
    <?php
}
?>
<!-- gör om!!!! så att info behålls när man trycker på back  -->
<form action="/../views/booking.php">
    <input type="submit" value="Back" />
</form>

<form action="/../../index.php">
    <input type="submit" value="Confirm booking" />
</form>
