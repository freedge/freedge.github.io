<?php
require __DIR__ . '/vendor/autoload.php';
use HttpSignatures\Context;

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
$context = new HttpSignatures\Context([
    'keys' => ['mykey' => file_get_contents(__DIR__ . '../.secret/key.pem')],
    'algorithm' => 'rsa-sha256',
    'headers' => ['(request-target)', 'Date'],
  ]);
$context->signer()->signWithDigest($response);


(new \Laminas\HttpHandlerRunner\Emitter\SapiEmitter())->emit($response);


?>
