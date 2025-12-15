<!-- arrival -->
 <form action="" method="post">
     <label for="arrival">Arrival:</label>
     <input
     type="date"
     name="arrival"
     id="arrival"
     min="2026-01-01"
     max="2026-01-31"
     >
     
     <!-- departure -->
     <label for="departure">Departure:</label>
     <input
     type="date"
     name="departure"
     id="departure"
     min="2026-01-01"
     max="2026-01-31"
     >
    </select>
    <button type="submit" class="dates-button">Continue</button>
</form>


<!-- if isset -> 

both checked?

departure after arrival date?

all good? -> next step -->


<!-- lägg till $_post för isset osv -->


<?php 

if (isset($_POST['arrival'], $_POST['departure'])){
    $arrival = $_POST['arrival'];
    $departure = $_POST['departure'];

    if ($departure <= $arrival) {
        echo 'The departure date can\'t be before the arrival date.';
    } 
    else {
        echo "
        Review your choices <br>
        You have booked The (room) <br>
        Dates: $arrival - $departure <br>
        Features: (feature 1), (feature 2) <br>
        Under the name (name) <br>
        Total price: (price) Bells"; ?>
        <button>proceed to booking</button>
   <?php } 
};
?>