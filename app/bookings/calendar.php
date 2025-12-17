

 <!-- calender  -->

     <link rel="stylesheet" href="/assets/styles/calendar.css">

<?php
// Days when the room is booked
$booked = [2, 6, 19, 27, 28];
?>

<section class="calendar">
    <?php
    for ($i = 1; $i <= 31; $i++) :
        if (in_array($i, $booked)) {
    ?><div class="day booked"><?= $i; ?></div>
        <?php
        } else if (($i % 7) === 0 || ($i % 7) === 6) {
        ?><div class="day weekend"><?= $i; ?></div>
        <?php
        } else {
        ?><div class="day"><?= $i; ?></div>
    <?php
        }
    endfor; ?>

</section>