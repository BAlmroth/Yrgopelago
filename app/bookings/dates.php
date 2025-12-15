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
    <button type="submit" class="dates-button">book</button>
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
        You have booked the (room) <br>
        Dates: $arrival - $departure <br>
        Under the name (name)";
    }
};
?>