<?php require __DIR__ . '/app/autoload.php'; ?>
<?php require __DIR__ . '/views/header.php'; ?>

<!-- checkIn -->
 <form action="app/bookings/booking.php" method="post">

    <label for="userId">Please provide your name:</label>
    <input 
    name="userId" 
    id="userId" 
    type="text">

     <label for="checkIn">Check In:</label>
     <input
     type="date"
     name="checkIn"
     id="checkIn"
     min="2026-01-01"
     max="2026-01-31"
     >
     
     <!-- checkOut -->
     <label for="checkOut">Check Out:</label>
     <input
     type="date"
     name="checkOut"
     id="checkOut"
     min="2026-01-01"
     max="2026-01-31"
     >

     <label for="room">Choose a room:</label>
        <select name="room" id="room">
        <?php $rooms = getRooms($database);
        foreach ($rooms as $room) { ?>
        <option value="<?= $room['name'] ?>"><?= $room['name']; ?></option> <?php } ?>
        </select>

    <button type="submit" class="dates-button">Continue</button>
</form>

<?php require __DIR__ . '/views/footer.php'; ?>