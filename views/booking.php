<?php require __DIR__ . '/../app/autoload.php'; ?>

<!-- checkIn -->
 <form action="/app/bookings/validateBooking.php" method="post">

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

     <?php $rooms = getRooms($database); ?>
     <label for="room">Choose a room:</label>
        <select name="room" id="room">
        <?php foreach ($rooms as $room) { ?>
        <option value="<?= $room['name'] ?>"><?= $room['name']; ?></option> <?php } ?>
        </select>

    <label for=""></label>

    <?php 
    $features = getFeatures($database); 
    foreach ($features as $feature) { ?>
        <label>
            <input 
                type="checkbox" 
                name="features[]" 
                value="<?= htmlspecialchars($feature['name']) ?>"
            >
            <?= htmlspecialchars($feature['name']) ?> (<?= $feature['cost'] ?> g)
        </label>
    <?php } ?>
    
    <button type="submit" class="dates-button">Continue</button>
</form>