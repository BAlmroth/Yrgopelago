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

                <label for="userId">
                    <h3>Please provide your name:</h3>
                </label>
                <input
                    name="userId"
                    id="userId"
                    type="text">

                <label for="checkIn">
                    <h3>Check In:</h3>
                </label>
                <input
                    type="date"
                    name="checkIn"
                    id="checkIn"
                    min="2026-01-01"
                    max="2026-01-31">

                <!-- checkOut -->
                <label for="checkOut">
                    <h3>Check Out:</h3>
                </label>
                <input
                    type="date"
                    name="checkOut"
                    id="checkOut"
                    min="2026-01-01"
                    max="2026-01-31">

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
            <label for="room">
                <h3>Avalible Acommodations:</h3>
            </label>
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
                    <img id="inImage" alt="">
                </div>

                <h3 id="roomName"></h3>
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
                                data-cost="<?= $feature['cost'] ?>">

                            <?= htmlspecialchars($feature['name']) ?> (<?= $feature['cost'] ?> g)
                        </label>
                    <?php } ?>
                </div>
            <?php } ?>
        </section>

        <section class="transferBox">

            <h3>Checkout</h3>

            <p id="totalPrice"><strong>Your total:</strong> 0 g</p>

            <label for="transferCode">
                <h4>insert transfercode:</h4>
            </label>
            <input
                name="transferCode"
                id="transferCode"
                type="text">

            <button type="submit" class="finalizeButton">Finalize booking</button>
            </form>
            <?php require __DIR__ . '/../app/bookings/transferService.php'; ?>
        </section>

    </section>

</section>

<script>
    window.rooms = <?= json_encode($rooms) ?>;
</script>

<script src="<?= $config['base_url'] ?>/app/script.js"></script>

<?php require __DIR__ . '/footer.php'; ?>