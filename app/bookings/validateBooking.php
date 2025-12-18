<?php
require __DIR__ . '/../autoload.php';

$errors = [];

if (isset($_POST['checkIn'], $_POST['checkOut'])) {

    $userId = $_POST['userId'];
    $checkIn = $_POST['checkIn'];
    $checkOut = $_POST['checkOut'];
    $room = $_POST['room'];

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


    if (count($errors) === 0) {

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

        $roomStatement = $database->prepare("SELECT id FROM rooms WHERE name = ?");
        $roomStatement->execute([$room]);
        $roomId = $roomStatement->fetchColumn();

        $statement = $database->prepare("
            INSERT INTO bookings 
            (user_id, room_id, check_in, check_out)
            VALUES (?, ?, ?, ?)
        ");

        $statement->execute([
            $userId,
            $roomId,
            $checkIn,
            $checkOut
        ]);
    }
}

        if (count($errors) !== 0) {
            foreach ($errors as $error) { ?>
            <div>
                <strong>UH OH</strong> <?= $error ?>
            </div>
            <?php } 
        } 
    }
?>
