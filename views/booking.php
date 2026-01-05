<?php require __DIR__ . '/../app/autoload.php'; ?>
<?php require __DIR__ . '/header.php'; ?>

    <section class="upperForm">
        
       <section class="leftBox">

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
               
               <?php
        $rooms = getRooms($database);   
        foreach ($rooms as $room) { ?>
            <div class="calendars" data-room-id="<?= $room['id'] ?>" style="display:none;">
                <?= htmlspecialchars($room['name']) ?>
                <?php
                $roomId = $room['id'];
                require __DIR__ . '/../app/bookings/calendar.php';
                ?>
            </div>
            <?php }
        ?>

    </section> 

    <section class="roomBox">

        <!-- ROOM SELECT  -->
        <label for="room">Choose a room:</label>
        <select name="room" id="room">
            <option value="">Select a room</option>
            <?php foreach ($rooms as $room) { ?>
                <option value="<?= $room['id'] ?>">
                    <?= ($room['name']) ?>
                </option>
            <?php } ?>
        </select>
            
            <!-- ROOM PREVIEW (ADDED) -->
            <div id="roomPreview" style="display:none;">
                
                <div class="roomImages">
                    <img id="outImage" alt="">
                    <img id="inImage"  alt="">
                </div>
                
                <h2 id="roomName"></h2>
                <p id="roomPrice"></p>
                <p id="roomDescription"></p>
                
            </div>
            
        </section>
        
    </section>
    
    <section class="lowerForm">

        <section class="features">
            
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
    </section>

    <section class="transferBox">

        
        <label for="transferCode">insert transfercode:</label>
        <input 
        name="transferCode" 
        id="transferCode" 
        type="text">
        
        <?php require __DIR__ . '/../app/bookings/transferService.php'; ?>

        <button type="submit" class="dates-button">Finalize booking</button>
    </form>
    </section>

</section>



<script>
const rooms = <?= json_encode($rooms) ?>;
const select = document.getElementById('room');

select.addEventListener('change', function () {
    const room = rooms.find(r => r.id == this.value);

    // Hide preview 
    if (!room) {
        document.getElementById('roomPreview').style.display = 'none';
        document.querySelectorAll('.calendars').forEach(c => c.style.display = 'none');
        return;
    }

    // Update preview
    document.getElementById('outImage').src = '/assets/images/' + room.outImage;
    document.getElementById('inImage').src  = '/assets/images/' + room.inImage;
    document.getElementById('roomName').textContent = room.name;
    document.getElementById('roomPrice').textContent = room.cost + ' g / night';
    document.getElementById('roomDescription').textContent = room.description;
    document.getElementById('roomPreview').style.display = 'block';

// calendar
    document.querySelectorAll('.calendars').forEach(cal => {
        cal.style.display = 'none';
    });

    const activeCalendar = document.querySelector(
        `.calendars[data-room-id="${room.id}"]`
    );

    if (activeCalendar) {
        activeCalendar.style.display = 'block';
    }
});

// transfercode service
const transferForm = document.getElementById('getTransferCode');
const result = document.getElementById('transferResult');

transferForm.addEventListener('submit', function (event) {
    event.preventDefault();

    const formData = new FormData(transferForm);
    const data = Object.fromEntries(formData.entries());

    fetch('https://www.yrgopelag.se/centralbank/withdraw', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            user: data.user,
            api_key: data.api_key,
            amount: data.amount
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            result.textContent = 'Error: ' + data.error;
        } else {
            result.textContent = 'Your transfer code: ' + data.transferCode;

            // optional auto-fill
            document.getElementById('transferCode').value = data.transferCode;
        }
    })
    .catch(() => {
        result.textContent = 'Something went wrong. Try again.';
    });
});

</script>
<?php require __DIR__ . '/footer.php'; ?>

<!-- //gör om så att när man har vlt och trycker continue ska man skickas till validateBooking, där kollas det om allt är rätt. om det inte är rätt skickas man tillbaka till booking.php och får ett error meddelande.
är det rätt -> printa valen på booking för validering.  -->