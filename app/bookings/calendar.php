<?php
// Hämta alla bokningar för detta rum
$statement = $database->prepare("
    SELECT check_in, check_out 
    FROM bookings 
    WHERE room_id = ?
");

$statement->execute([$roomId]);
$bookings = $statement->fetchAll(PDO::FETCH_ASSOC);

// Bygg lista med bokade dagar
$bookedDays = [];

foreach ($bookings as $booking) {
    $start = date('j', strtotime($booking['check_in']));
    $end   = date('j', strtotime($booking['check_out']));

    for ($d = $start; $d < $end; $d++) {
        $bookedDays[] = $d;
    }
}
?>

<section class="calendar">
<?php
for ($day = 1; $day <= 31; $day++) {

    $classes = ['day'];

    if (in_array($day, $bookedDays)) {
        $classes[] = 'booked';
    } elseif ($day % 7 === 0 || $day % 7 === 6) {
        $classes[] = 'weekend';
    }

    echo '<div class="' . implode(' ', $classes) . '">' . $day . '</div>';
}
?>
</section>
