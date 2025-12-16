
<?php 

if (isset($_POST['userId'], $_POST['checkIn'], $_POST['checkOut'], $_POST['room'])){
    $checkIn = $_POST['checkIn'];
    $checkOut = $_POST['checkOut'];
    $room = $_POST['room'];
    $userId = htmlspecialchars(trim($_POST['userId']));

    if ($checkOut <= $checkIn) {
        echo 'The checkOut date can\'t be before the checkIn date.';
    } 
    else {
        echo "
        Review your choices <br>
        You have booked The $room <br>
        Dates: $checkIn - $checkOut <br>
        Features: (feature 1), (feature 2) <br>
        Under the name $userId <br>
        Total price: (price) Bells"; ?>
        <button>proceed to booking</button>
        <button>back</button>

   <?php } 
};
?>