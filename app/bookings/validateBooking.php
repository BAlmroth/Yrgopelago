<?php
require __DIR__ . '/../autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$errors = [];

if (isset($_POST['checkIn'], $_POST['checkOut'])) {

    $userId = $_POST['userId'];
    $checkIn = $_POST['checkIn'];
    $checkOut = $_POST['checkOut'];
    $room = $_POST['room'];

    $roomId = (int) $_POST['room'];

    $bookedFeatures = $_POST['features'] ?? [];
    $transferCode = $_POST['transferCode'] ?? '';

    if ($checkIn === '') $errors[] = "Please select a check-in date.";
    if ($checkOut === '') $errors[] = "Please select a check-out date.";
    if ($checkOut <= $checkIn) $errors[] = "The check-out date can't be before the check-in date.";
    if ($userId === '') $errors[] = "Please insert your name (user id)";

    if (count($errors) === 0) {

        $bookings = getBookings($database);
        $isBooked = false;

        foreach ($bookings as $booking) {
            $dbCheckIn = $booking['check_in'];
            $dbCheckOut = $booking['check_out'];
            $dbRoomId = $booking['room_id'];

            if ($dbRoomId == $roomId && $checkIn < $dbCheckOut && $checkOut > $dbCheckIn) {
                $errors[] = "The room is already booked during this timeframe.";
                $isBooked = true;
                break;
            }
        }

        if (!$isBooked) {

            // User stays logic
            $userStatement = $database->prepare("SELECT id, stays FROM users WHERE name = ?");
            $userStatement->execute([$userId]);
            $user = $userStatement->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $newStays = $user['stays'] + 1;
                $updateStmt = $database->prepare("UPDATE users SET stays = ? WHERE id = ?");
                $updateStmt->execute([$newStays, $user['id']]);
                $dbUserId = $user['id'];
            } else {
                $insertStmt = $database->prepare("INSERT INTO users (name, stays) VALUES (?, ?)");
                $insertStmt->execute([$userId, 1]);
                $dbUserId = $database->lastInsertId();
            }

            // Room info
            $roomStatement = $database->prepare(
                "SELECT id, cost FROM rooms WHERE id = ?"
            );
            $roomStatement->execute([$roomId]);
            $roomRow = $roomStatement->fetch(PDO::FETCH_ASSOC);

            if (!$roomRow) {
                $errors[] = "Selected room does not exist.";
            }


            if (empty($errors)) {

                // Booking to DB
                $statement = $database->prepare("
                    INSERT INTO bookings 
                    (user_id, room_id, check_in, check_out)
                    VALUES (?, ?, ?, ?)
                ");
                $statement->execute([$dbUserId, $roomId, $checkIn, $checkOut]);

                // Booking features
                $bookingId = $database->lastInsertId();
                if (!empty($bookedFeatures)) {
                    $featureStatement = $database->prepare("
                        INSERT INTO booking_features (booking_id, feature_id)
                        VALUES (?, ?)
                    ");
                    foreach ($bookedFeatures as $featureId) {
                        $featureStatement->execute([$bookingId, $featureId]);
                    }
                }

                // Total price
                $totalPrice = $roomRow['cost'];
                if (!empty($bookedFeatures)) {
                    $placeholders = implode(',', array_fill(0, count($bookedFeatures), '?'));
                    $costStatement = $database->prepare("
                        SELECT SUM(cost) as featuresCost FROM features WHERE id IN ($placeholders)
                    ");
                    $costStatement->execute($bookedFeatures);

                    // ✅ FIX 4: NULL-safe
                    $featuresCost = (int) $costStatement->fetchColumn();
                    $totalPrice += $featuresCost;
                }

                $client = new Client(['base_uri' => 'https://www.yrgopelag.se/centralbank/']);
                $hotelUser = 'Benita';
                $apiKey = $_ENV['API_KEY'];

                try {
                    // Validate transfer code
                    $res = $client->post('transferCode', [
                        'json' => [
                            'transferCode' => $transferCode,
                            'totalCost' => $totalPrice
                        ]
                    ]);
                    $validate = json_decode($res->getBody(), true);
                    if (isset($validate['error'])) throw new Exception($validate['error']);

                    // Deposit
                    $res = $client->post('deposit', [
                        'json' => [
                            'user' => $hotelUser,
                            'transferCode' => $transferCode
                        ]
                    ]);
                    $deposit = json_decode($res->getBody(), true);
                    if (!isset($deposit['status']) || $deposit['status'] !== "success") {
                        throw new Exception($deposit['error'] ?? "Deposit failed");
                    }

                    // Receipt
                    $featuresForReceipt = [];

                    foreach ($bookedFeatures as $id) {
                        $stmt = $database->prepare("SELECT category AS activity, tier FROM features WHERE id = ?");
                        $stmt->execute([$id]);
                        $feature = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($feature) {
                            $featuresForReceipt[] = $feature;
                        }
                    }

                    $res = $client->post('receipt', [
                        'json' => [
                            'user' => $hotelUser,
                            'api_key' => $apiKey,
                            'guest_name' => $userId,
                            'arrival_date' => $checkIn,
                            'departure_date' => $checkOut,
                            'features_used' => $featuresForReceipt,
                            'star_rating' => 1
                        ]
                    ]);

                    $receipt = json_decode($res->getBody(), true);
                    if (!isset($receipt['status']) || $receipt['status'] !== 'success') {
                        throw new Exception($receipt['error'] ?? "Receipt failed");
                    }


                // reciept

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

                <?php
                } catch (Exception $e) {
                    $errors[] = $e->getMessage();
                }
            }
        }
    }
}

// Display errors
if (!empty($errors)) {
    foreach ($errors as $error) { ?>
        <div>
            <strong>UH OH</strong> <?= htmlspecialchars($error) ?>
        </div>
    <?php }
    exit;
}
?>

<form action="<?= $config['base_url'] ?>/views/booking.php">
    <input type="submit" value="Back" /> 
</form>
