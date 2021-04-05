<?php
require __DIR__ . '/vendor/autoload.php';

$psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();

$creator = new \Nyholm\Psr7Server\ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);

$body = array("hello" => "world");
$serverRequest = $creator->fromGlobals();

$responseBody = $psr17Factory->createStream(json_encode($body));
$response = $psr17Factory->createResponse(200)
  ->withBody($responseBody);
(new \Laminas\HttpHandlerRunner\Emitter\SapiEmitter())->emit($response);


?>
