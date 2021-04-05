<?php
require __DIR__ . '/vendor/autoload.php';
use HttpSignatures\Context;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\SapiEmitter;

$body = array(
  "hello" => "world",
  "ip" => $_REQUEST['REMOTE_ADDR']
);

try {
  $body["pubkey"] = file_get_contents(__DIR__ . '/../.secret/cert.pem');
} catch (Exception $e) {
  $body["error"] = $e->getMessage();
}




$response = new JsonResponse($body);
$context = new HttpSignatures\Context([
            'keys' => ['mykey' => file_get_contents(__DIR__ . '/../.secret/key.pem')],
	    'algorithm' => 'rsa-sha256',
	    'headers' => [],
]);

// take quite some time though
$response = $context->signer()->sign($response);


(new SapiEmitter())->emit($response);

?>
