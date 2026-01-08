<?php require __DIR__ . '/../app/autoload.php'; ?>
<?php require __DIR__ . '/header.php'; ?>
<section class="bookingMain">

    <div class="bookingIntro">
        <h2>Book your stay</h2>
    </div>

    <section class="upperForm">
        
        <section class="leftBox">
            
            <!-- checkIn -->
            <form action="<?= $config['base_url'] ?>/app/bookings/validateBooking.php" method="post">
                
                <label for="userId"><h3>Please provide your name:</h3></label>
                <input 
                name="userId" 
                id="userId" 
                type="text">
                
                <label for="checkIn"><h3>Check In:</h3></label>
                <input
                type="date"
                name="checkIn"
                id="checkIn"
                min="2026-01-01"
                max="2026-01-31"
                >
                
                <!-- checkOut -->
                <label for="checkOut"><h3>Check Out:</h3></label>
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
                        <h3>Avalability</h3>
                        <?php
                        $roomId = $room['id'];
                        require __DIR__ . '/../app/bookings/calendar.php';
                        ?>
                    </div>
                <?php }
        ?>

        </section> 

<section class="roomBox">
    
    <!-- room select -->
    <label for="room"><h3>Avalible Acommodations:</h3></label>
    <select name="room" id="room">
        <option value="">Select a room</option>
        <?php foreach ($rooms as $room) { ?>
            <option value="<?= $room['id'] ?>" data-cost="<?= $room['cost'] ?>">

                <?= ($room['name']) ?>
            </option>
            <?php } ?>
        </select>
        
 <!-- room prev -->
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
    
    <section class="featuresIntro">
        <h2>Add Features</h2>
        <h4>Want to spice up your stay wifth some activites?</h4>
    </section>

<section class="features">
    <?php
    $features = getFeatures($database);
    $grouped = [];

    foreach ($features as $feature) {
        $grouped[$feature['category']][] = $feature;
    }

    foreach ($grouped as $category => $items) { ?>
        <div class="featureCard">
            <h3><?= htmlspecialchars($category) ?></h3>

            <?php foreach ($items as $feature) { ?>
                <label>
                    <input
                    type="checkbox"
                    name="features[]"
                    value="<?= $feature['id'] ?>"
                    data-cost="<?= $feature['cost'] ?>"
                >

                    <?= htmlspecialchars($feature['name']) ?> (<?= $feature['cost'] ?> g)
                </label>
            <?php } ?>
        </div>
    <?php } ?>
</section>


    
    <section class="transferBox">
        
        <p id="totalPrice"><strong>Your total:</strong> 0 g</p>

        <label for="transferCode"><h3>insert transfercode:</h3></label>
        <input 
        name="transferCode" 
        id="transferCode" 
        type="text">
        
        <button type="submit" class="dates-button">Finalize booking</button>
    </form>
    <?php require __DIR__ . '/../app/bookings/transferService.php'; ?>
</section>

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

            //autofill
            document.getElementById('transferCode').value = data.transferCode;
        }
    })
    .catch(() => {
        result.textContent = 'Something went wrong. Try again.';
    });
});

// visa kostnad
const roomSelect = document.getElementById('room');
const featureCheckboxes = document.querySelectorAll('input[name="features[]"]');
const totalEl = document.getElementById('totalPrice');

const checkInInput = document.getElementById('checkIn');
const checkOutInput = document.getElementById('checkOut');

function getNights() {
    if (!checkInInput.value || !checkOutInput.value) return 0;

    const checkIn = new Date(checkInInput.value);
    const checkOut = new Date(checkOutInput.value);

    const diffTime = checkOut - checkIn;
    const diffDays = diffTime / (1000 * 60 * 60 * 24);

    return diffDays > 0 ? diffDays : 0;
}

function updateTotal() {
    let total = 0;

    const nights = getNights();

    // room cost × nights
    const selectedRoom = roomSelect.options[roomSelect.selectedIndex];
    if (selectedRoom && selectedRoom.dataset.cost && nights > 0) {
        total += Number(selectedRoom.dataset.cost) * nights;
    }

    // features
    featureCheckboxes.forEach(cb => {
        if (cb.checked) {
            total += Number(cb.dataset.cost);
        }
    });

    totalEl.innerHTML = `<strong>Your total:</strong> ${total} g`;
}

roomSelect.addEventListener('change', updateTotal);
featureCheckboxes.forEach(cb => cb.addEventListener('change', updateTotal));
checkInInput.addEventListener('change', updateTotal);
checkOutInput.addEventListener('change', updateTotal);


</script>
<?php require __DIR__ . '/footer.php'; ?>