<?php

$rooms = getRooms($database);
$features = getFeatures($database);

$roomName = '';
$roomCost = 0;
foreach ($rooms as $r) {
    if ($r['id'] == $roomId) {
        $roomName = $r['name'];
        $roomCost = $r['cost'];
        break;
    }
}

$featureNames = [];
$totalPrice = $roomCost;

if (!empty($bookedFeatures)) {
    foreach ($bookedFeatures as $fId) {
        foreach ($features as $f) {
            if ($f['id'] == $fId) {
                $featureNames[] = $f['name'];
                $totalPrice += $f['cost'];
            }
        }
    }
}

?>

<p>Your room has been booked!</p>
<p>Your receipt:</p>
<p>Name: <?= htmlspecialchars($userId) ?></p>
<p>Room: <?= htmlspecialchars($roomName) ?></p>
<p>Check-in: <?= htmlspecialchars($checkIn) ?></p>
<p>Check-out: <?= htmlspecialchars($checkOut) ?></p>
<p>Features: <?= !empty($featureNames) ? implode(', ', $featureNames) : 'None' ?></p>
<p>Total cost: <?= htmlspecialchars($totalPrice) ?> g</p>