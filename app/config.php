<?php

declare(strict_types=1);


$base_url = ($_SERVER['HTTP_HOST'] === 'localhost:8000' || $_SERVER['HTTP_HOST'] === 'localhost') ? '' : '/yrgopelago';

return [
    'title' => 'Project Name',
    'database_path' => sprintf('sqlite:%s/database/yrgopelago.db', __DIR__),
    'base_url' => $base_url,
];

?>