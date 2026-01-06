<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $config['base_url'] ?>/assets/styles/style.css">
    <link rel="stylesheet" href="<?= $config['base_url'] ?>/assets/styles/booking.css">
    <link rel="stylesheet" href="<?= $config['base_url'] ?>/assets/styles/calendar.css">
    <link rel="stylesheet" href="<?= $config['base_url'] ?>/assets/styles/headFoot.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bubblegum+Sans&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <title>Document</title>
</head>
<body>

  <header class="header">
    <div class="logoBar">
        <img class="logo" src="../assets/images/rooster.svg" alt="rooster logo">
        <h3>Stardew Hotel</h3>
    </div>
    <nav class="nav">
      <a href="<?= $config['base_url'] ?>/index.php">Home</a>
      <a href="#">About</a>
      <a href="<?= $config['base_url'] ?>/views/booking.php">Booking</a>
    </nav>
  </header>