<?php
session_start();
require __DIR__ . '/autoload.php';
require __DIR__ . '/../views/header.php';

if (!($_SESSION['is_admin'] ?? false)) {
    header('Location: ' . $config['base_url'] . '/views/login.php');
    exit;
}

// room logic
if (isset($_POST['update_room'])) {

    $id   = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    $cost = filter_var($_POST['cost'], FILTER_VALIDATE_INT);

    $name = trim($_POST['name']);
    $name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);

    if ($id !== false && $cost !== false && $name !== '') {
        $stmt = $database->prepare("
            UPDATE rooms
            SET name = :name,
                cost = :cost
            WHERE id = :id
        ");

        $stmt->execute([
            ':name' => $name,
            ':cost' => $cost,
            ':id'   => $id
        ]);
    }
}

// feature logic
if (isset($_POST['update_feature'])) {

    $id   = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    $cost = filter_var($_POST['cost'], FILTER_VALIDATE_INT);

    if ($id !== false && $cost !== false) {
        $stmt = $database->prepare("
            UPDATE features
            SET cost = :cost
            WHERE id = :id
        ");

        $stmt->execute([
            ':cost' => $cost,
            ':id'   => $id
        ]);
    }
}

// star logic
if (isset($_POST['update_stars'])) {

    $stars = filter_var($_POST['stars'], FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => 5]
    ]);

    if ($stars !== false) {
        $stmt = $database->prepare("
            UPDATE stars
            SET stars = :stars
            WHERE id = 1
        ");

        $stmt->execute([
            ':stars' => $stars
        ]);
    }
}

$rooms = getRooms($database);
$features = getFeatures($database);
$stars = $database->query("SELECT stars FROM stars WHERE id = 1")->fetchColumn();
?>

<section class="admin">

    <h1>Admin</h1>
    
   <div class="adminRooms">

       <h3>Rooms</h3>
       
       <?php foreach ($rooms as $room): ?>
        <form method="post">
            <input type="hidden" name="id" value="<?= (int) $room['id'] ?>">
            
            <label>
                Name:
                <input
                type="text"
                name="name"
                value="<?= htmlspecialchars(trim($room['name']), ENT_QUOTES, 'UTF-8') ?>"
                >
            </label>
            
            <label>
                Price:
                <input type="number" name="cost" value="<?= (int) $room['cost'] ?>">
            </label>
            
            <button type="submit" name="update_room">Save</button>
        </form>
        <hr>
        <?php endforeach; ?>
    </div>
        
        
    <div class="adminFeatures">

        <h3>Features</h3>
        
        <?php foreach ($features as $feature): ?>
            <form method="post">
                <input type="hidden" name="id" value="<?= (int) $feature['id'] ?>">
                
                <strong>
                    <?= htmlspecialchars($feature['name'], ENT_QUOTES, 'UTF-8') ?>
                </strong>
                (
                    <?= htmlspecialchars($feature['category'], ENT_QUOTES, 'UTF-8') ?>
                    )
                    
                    <input type="number" name="cost" value="<?= (int) $feature['cost'] ?>">
                    
                    <button type="submit" name="update_feature">Save</button>
                </form>
                <?php endforeach; ?>
                
    </div>
                
    <div class="adminStars">

        <h3>Star rating</h3>
        
        <form method="post">
            <input
            type="number"
            name="stars"
            min="1"
            max="5"
            value="<?= (int) $stars ?>"
            >
            <button type="submit" name="update_stars">Save</button>
        </form>
    </div>
        
        <a href="<?= $config['base_url'] ?>/app/logout.php">Logout</a>

    </section>


<?php require __DIR__ . '/../views/footer.php'; ?>