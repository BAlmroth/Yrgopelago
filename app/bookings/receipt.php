<?php

$rooms = getRooms($database);
$features = getFeatures($database);

// Find the booked room info
$roomName = '';
$roomCost = 0;
foreach ($rooms as $r) {
    if ($r['id'] == $roomId) {
        $roomName = $r['name'];
        $roomCost = $r['cost'];
        break;
    }
}

// Calculate number of nights
$checkInDate = new DateTime($checkIn);
$checkOutDate = new DateTime($checkOut);
$nights = $checkOutDate->diff($checkInDate)->days;

// room cost × nights
$totalPrice = $roomCost * max($nights, 1);

//features cost (one-time cost)
$featureNames = [];
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

// Check user loyalty to show discount
$loyaltyDiscount = 0;
$userStatement = $database->prepare("SELECT stays FROM users WHERE name = ?");
$userStatement->execute([$userId]);
$user = $userStatement->fetch(PDO::FETCH_ASSOC);

//already updated
if ($user && $user['stays'] > 1) {
    $loyaltyDiscount = 1;
    $totalPrice -= $loyaltyDiscount;
}

?>

<p>Your room has been booked!</p>
<p>Your receipt:</p>
<p><strong>Name:</strong> <?= htmlspecialchars($userId) ?></p>
<p><strong>Room:</strong> <?= htmlspecialchars($roomName) ?></p>
<p><strong>Check-in:</strong> <?= htmlspecialchars($checkIn) ?></p>
<p><strong>Check-out:</strong> <?= htmlspecialchars($checkOut) ?></p>
<p><strong>Features:</strong> <?= !empty($featureNames) ? implode(', ', $featureNames) : 'None' ?></p>

<p>
    <strong>Total cost:</strong> <?= htmlspecialchars($totalPrice) ?> g
    <?php if ($loyaltyDiscount > 0): ?>
        <span style="color:green;">(-<?= $loyaltyDiscount ?>g loyalty discount)</span>
    <?php endif; ?>
</p>