<?php
require __DIR__ . '/vendor/autoload.php';
use HttpSignatures\Context;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\SapiEmitter;

// generate a nice body
$body = array(
  "request" => "sign.php",
  "ip" => $_SERVER['REMOTE_ADDR']
);

try {
  $body["pubkey"] = file_get_contents(__DIR__ . '/../.secret/pubkey');
} catch (Exception $e) {
  $body["error"] = $e->getMessage();
}

$response = new JsonResponse($body, 200, array("date" => gmdate('D, d M Y H:i:s T')));

// add the signature
$context = new HttpSignatures\Context([
            'keys' => ['mykey' => file_get_contents(__DIR__ . '/../.secret/key.pem')],
	    'algorithm' => 'rsa-sha256',
	    'headers' => ['Date'],
]);

$response = $context->signer()->signWithDigest($response);

// can be verified with
//   $context->verifier()->isSigned($response)


// send the response
(new SapiEmitter())->emit($response);

?>
