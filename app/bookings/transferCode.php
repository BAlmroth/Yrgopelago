<?php 
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../autoload.php';

use GuzzleHttp\Client;

$client = new GuzzleHttp\Client(['base_uri' => 'https://www.yrgopelag.se/centralbank/']);

$response = $client->get('startCode');

$data = json_decode(
    $response->getBody()->getContents(),
    true
);

echo '<pre>';
print_r($data);
echo '</pre>';

?>