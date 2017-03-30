<?php

// autoload
require_once dirname(__FILE__) . "/../tests/bootstrap.php";
require_once dirname(__FILE__) . "/SampleRepository.php";

$options = $_GET;
$provider = new \MinhD\OAIPMH\ServiceProvider(
    new SampleRepository()
);
$provider->setOptions($options);

$response = $provider->get()->getResponse();

foreach ($response->getHeaders() as $k => $values) {
    foreach ($values as $v) {
        header("$k: $v", false);
    }
}
echo (string) $response->getBody();