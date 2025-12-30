<?php require __DIR__ . '/app/autoload.php'; ?>
<?php require __DIR__ . '/views/header.php'; ?>


<form action="views/booking.php">
    <input type="submit" value="Go to booking" />
</form>

<?php 
        $bookings = getBookings($database);
        foreach ($bookings as $booking) {
            
            echo '<pre>';
            print_r($booking);
        }
?>

<?php require __DIR__ . '/views/footer.php'; ?>