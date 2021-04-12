<?php

require __DIR__.'/vendor/autoload.php';
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\SapiEmitter;

// could not find a poison emoji, so use what Bing suggested
$body = [
    'request' => 'poison.php',
    'content' => '🐟',
];

// generate an arbitrary header to try to mess up with Squid
if (isset($_GET['cus'])) {
    header(base64_decode($_GET['cus']));
}

$response = new JsonResponse($body, 200, [
    'date' => gmdate('D, d M Y H:i:s T'),
]);

(new SapiEmitter())->emit($response);
