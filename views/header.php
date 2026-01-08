<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?= $config['base_url'] ?>/assets/styles/style.css">
  <link rel="stylesheet" href="<?= $config['base_url'] ?>/assets/styles/booking.css">
  <link rel="stylesheet" href="<?= $config['base_url'] ?>/assets/styles/calendar.css">
  <link rel="stylesheet" href="<?= $config['base_url'] ?>/assets/styles/headFoot.css">
  <link rel="stylesheet" href="<?= $config['base_url'] ?>/assets/styles/admin.css">
  <link rel="stylesheet" href="<?= $config['base_url'] ?>/assets/styles/login.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bubblegum+Sans&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
  <title>Stardew Hotel</title>
</head>

<body>
  <header class="header">
    <div class="logoBar">
      <a href="<?= $config['base_url'] ?>/index.php"><img class="logo" src="<?= $config['base_url'] ?>/assets/images/rooster.svg" alt="rooster logo"></a>
      <h3>Stardew Hotel</h3>
    </div>

    <div class="starRating">
      <?php
      $stars = getStars($database);

      for ($i = 0; $i < $stars; $i++){ ?>
        <img src="<?= $config['base_url'] ?>/assets/images/star.png" alt="Star" class="starImage">
      <?php } ?>
    </div>

    <nav class="nav">
      <a href="<?= $config['base_url'] ?>/index.php">Home</a>
      <a href="#">about</a>
      <a href="<?= $config['base_url'] ?>/views/booking.php">Booking</a>
    </nav>
  </header>