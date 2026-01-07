<?php

declare(strict_types=1);

require __DIR__ . '/autoload.php';

unset($_SESSION['is_admin']);

header('Location: ' . $config['base_url'] . '/index.php');

exit;