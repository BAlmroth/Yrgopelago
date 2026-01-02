<?php require __DIR__ . '/../app/autoload.php'; ?>
<?php require __DIR__ . '/header.php'; ?>

    <section class="upperForm">
        
        <?php
        $rooms = getRooms($database);
        
        foreach ($rooms as $room) { ?>
            <div class="calendars"> <?php
            echo ($room['name']);
            
            $roomId = $room['id'];
            require __DIR__ . '/../app/bookings/calendar.php'; ?>
            </div> <?php
        }
        ?>
    </section>

<!-- checkIn -->
<form action="<?= $config['base_url'] ?>/app/bookings/validateBooking.php" method="post">

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


    <?php 
    $features = getFeatures($database); 
    foreach ($features as $feature) { ?>
        <label>
    <input 
        type="checkbox" 
        name="features[]"
        value="<?= $feature['id'] ?>"
    >
        <?= ($feature['name']) ?> (<?= $feature['cost'] ?> g)
            </label>
        <?php } ?>

    <label for="transferCode">insert transfercode:</label>
        <input 
        name="transferCode" 
        id="transferCode" 
        type="text">
        
    <button type="submit" class="dates-button">Finalize booking</button>
</form>


<!-- //gör om så att när man har vlt och trycker continue ska man skickas till validateBooking, där kollas det om allt är rätt. om det inte är rätt skickas man tillbaka till booking.php och får ett error meddelande.
är det rätt -> printa valen på booking för validering.  -->