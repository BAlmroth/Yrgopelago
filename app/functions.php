<?php 

//get rooms från sql
function getRooms ($database) {
    $statement = $database->query("
    SELECT * 
    FROM rooms 
    ORDER BY id
    ");
    
    return $statement->fetchAll(PDO::FETCH_ASSOC);

}

// function getTotalPrice ($database) {
//     $statement = database ->query("
//     SELECT *
//     FROM rooms
//     INNER JOIN 
//     ")
// }

// get booked dates and rooms
function getBookings ($database) {
    $statement = $database->query("
    SELECT * 
    FROM bookings
    INNER JOIN rooms
    ON bookings.room_id = rooms.id
    ORDER BY rooms.id ASC
    ");
    
    return $statement->fetchAll(PDO::FETCH_ASSOC);

}

//get features från sql
function getFeatures($database) {
    $statement = $database->query("
        SELECT id, name, cost, category
        FROM features
        WHERE name IN (
            'fishing trip',
            'fresh farmstyle-breakfast',
            'yahtzee',
            'ping pong table',
            'olympic pool',
            'trike',
            'four-wheeled motorized beast',
            'spend a day as a farmer',
            'visit the scull cavern'
        )
        ORDER BY Category ASC
    ");
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

?>