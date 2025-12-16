<?php require __DIR__ . '/../autoload.php'; ?>

<?php $rooms = getRooms($database);?>

<?php

    foreach ($rooms as $room) { ?>
        
        <div class="room">
            <h3><?= $room['name']; ?></h3>
            <h4><?= $room['tier']; ?></h4>
            <h5><?= 'cost:'.' '.$room['cost'].' '.'Bells'; ?></h5>
            <p><?= $room['description']; ?></p>
        </div>
        
    <?php };
?> 