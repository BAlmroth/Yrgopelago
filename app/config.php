<?php
declare(strict_types=1);


$isLocal = $_SERVER['HTTP_HOST'] === 'localhost:8000' || $_SERVER['HTTP_HOST'] === 'localhost';
$base_url = $isLocal ? '' : '/yrgopelago';

return [
    'title' => 'Project Name',
    'database_path' => sprintf('sqlite:%s/database/yrgopelago.db', __DIR__),
    'base_url' => $base_url,
];
?>