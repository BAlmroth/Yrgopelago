<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

$rooms = getRooms($database);
?>

<section class="hero">
    <div class="heroText">
        <h1>Stardew Hotel</h1>
        <h4>Leave your worries behind, get a taste of the simple life</h4>
    </div>
    <div class="divider"></div>
</section>


<section class="content">
    <div class="intro">
        <h2>Welcome to Pelican Island!</h2>
        <h4>Located on the marvelous Pelican Island, in the wast ocean of nothingness lays The Stardew Hotel. Enjoy a vacation at the beach filled with luxury, or experience the life of a true farmer. <br></br> Everything is possible at Pelican Island!</h4>
    </div>

    <div class="aboutRooms">
        <h2>Explore our rooms</h2>
        <div class="roomsContainer">
            <?php foreach ($rooms as $room) { ?>
                <div class="roomCard">
                    <div class="roomTop">
                        <div class="roomContent">
                            <h3><?= $room['name']; ?></h3>
                            <p class="roomTier"><?= $room['tier']; ?></p>
                            <p><?= $room['description']; ?></p>
                            <p class="roomPrice"><?= $room['cost']; ?>g / night</p>
                        </div>
                        <div class="roomImages">
                            <img src="assets/images/<?= $room['outImage']; ?>" alt="<?= $room['name']; ?> Outside">
                            <img src="assets/images/<?= $room['inImage']; ?>" alt="<?= $room['name']; ?> Inside">
                        </div>
                    </div>


                    <div class="roomBottom">
                        <div class="roomCalendar">
                            <h4>Avalible dates:</h4>
                            <?php
                            $roomId = $room['id'];
                            include __DIR__ . '/app/bookings/calendar.php';
                            ?>
                            <div class="calendarLegend">
                                <div class="legendItem">
                                    <div class="colorBox booked"></div>
                                    <span>Booked</span>
                                </div>
                                <div class="legendItem">
                                    <div class="colorBox free"></div>
                                    <span>Free</span>
                                </div>
                            </div>
                        </div>

                        <a href="<?= $config['base_url'] ?>/views/booking.php" class="bookingButton">Go to Booking</a>
                    </div>

                </div>

            <?php } ?>
        </div>


    </div>
</section>

<?php

?>
<?php require __DIR__ . '/views/footer.php'; ?>