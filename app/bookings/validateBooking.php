<?php
require __DIR__ . '/../autoload.php';
require __DIR__ . '/../../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$errors = [];

if (isset($_POST['checkIn'], $_POST['checkOut'])) {

    $userId = $_POST['userId'];
    $checkIn = $_POST['checkIn'];
    $checkOut = $_POST['checkOut'];
    $room = $_POST['room'];
    $selectedFeatures = $_POST['features'] ?? [];
    $transferCode = $_POST['transferCode'] ?? '';

    if ($checkIn === '') {
        $errors[] = "Please select a check-in date.";
    }

    if ($checkOut === '') {
        $errors[] = "Please select a check-out date.";
    }

    if ($checkOut <= $checkIn) {
        $errors[] = "The check-out date can't be before the check-in date.";
    }

    if ($userId === '') {
        $errors[] = "Please insert your name (user id)";
    }


    if (count($errors) === 0) { //om allt ser bra ut, inga fel någonsins ->

        $bookings = getBookings($database);
        $isBooked = false;

        foreach ($bookings as $booking) {

            $dbCheckIn = $booking['check_in'];
            $dbCheckOut = $booking['check_out'];
            $dbRoomName = $booking['name'];

            if (
                $dbRoomName === $room &&
                $checkIn < $dbCheckOut &&
                $checkOut > $dbCheckIn
            ) {
                $errors[] = "The room is already booked during this timeframe.";
                $isBooked = true;
                break;
            }
        }

if (!$isBooked) {

        //användare, stays?
    $userStatement = $database->prepare("SELECT id, stays FROM users WHERE name = ?");
    $userStatement->execute([$userId]);
    $user = $userStatement->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // user exists → increment stays
        $newStays = $user['stays'] + 1;
        $updateStmt = $database->prepare("UPDATE users SET stays = ? WHERE id = ?");
        $updateStmt->execute([$newStays, $user['id']]);
        $dbUserId = $user['id'];
    } else {
        // user does not exist → insert new
        $insertStmt = $database->prepare("INSERT INTO users (name, stays) VALUES (?, ?)");
        $insertStmt->execute([$userId, 1]);
        $dbUserId = $database->lastInsertId();
    }


    $roomStatement = $database->prepare("SELECT id, cost FROM rooms WHERE name = ?");
    $roomStatement->execute([$room]);
    $roomRow = $roomStatement->fetch(PDO::FETCH_ASSOC);
    $roomId = $roomRow['id'];

//bokning till db
    $statement = $database->prepare("
        INSERT INTO bookings 
        (user_id, room_id, check_in, check_out)
        VALUES (?, ?, ?, ?)
    ");
    $statement->execute([
        $dbUserId,
        $roomId,
        $checkIn,
        $checkOut
    ]);

    //  features till db
    $bookingId = $database->lastInsertId(); 
    if (!empty($selectedFeatures)) {
        $featureStatement = $database->prepare("
            INSERT INTO booking_features (booking_id, feature_id)
            VALUES (?, ?)
        ");
        foreach ($selectedFeatures as $featureId) {
            $featureStatement->execute([$bookingId, $featureId]);
        }
    }

    //totalt price
    $totalPrice = $roomRow['cost'];
    if (!empty($selectedFeatures)) {
        $placeholders = implode(',', array_fill(0, count($selectedFeatures), '?'));
        $costStatement = $database->prepare("
            SELECT SUM(cost) as featuresCost FROM features WHERE id IN ($placeholders)
        ");
        $costStatement->execute($selectedFeatures);
        $featuresCost = $costStatement->fetchColumn();
        $totalPrice += $featuresCost;
        echo $totalPrice;
    }

    // validate transferCode
if ($transferCode === '') {
    $errors[] = "Please insert a transfer code.";
} else {
    try {
        $client = new Client([
            'base_uri' => 'https://www.yrgopelag.se/centralbank/',
        ]);

        $response = $client->post('transferCode', [
            'json' => [
                'transferCode' => $transferCode,
                'totalCost' => $totalPrice,
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        if (!isset($result['status']) || $result['status'] !== 'success') {
            $errors[] = "Transfer code validation failed.";
        }

    } catch (RequestException $e) {
        $errors[] = "Invalid or already used transfer code.";
    }
}

// deposit transfer code (get paid)
if (count($errors) === 0) {
    try {
        $response = $client->post('deposit', [
            'json' => [
                'user' => 'Benita',
                'transferCode' => $transferCode,
            ]
        ]);

        $depositResult = json_decode($response->getBody()->getContents(), true);

        if (!isset($depositResult['status']) || $depositResult['status'] !== 'success') {
            $errors[] = "Deposit failed.";
        }

    } catch (RequestException $e) {
        $errors[] = "Could not complete payment.";
    }
}

}

}

        if (count($errors) !== 0) {
            foreach ($errors as $error) { ?>
            <div>
                <strong>UH OH</strong> <?= $error ?>
            </div>
            
            <!-- byt ut back till en session så att man har kvar info -->
            <?php } 
        } 
    }
    ?>
    
    <form action="/../../views/booking.php">
        <input type="submit" value="Back" /> 
    </form>
