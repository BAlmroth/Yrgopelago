<?php 

function getRooms ($database) {

    $statement = $database->query('SELECT * FROM rooms ORDER BY id');
    
    return $statement->fetchAll(PDO::FETCH_ASSOC);

}
// getRooms($database);

?>