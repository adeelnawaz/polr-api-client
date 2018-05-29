<?php

define('POLR_API_URL', 'http://polr-api.test');     // Put your Polr API URL here
define('POLR_API_KEY', 'polr-api-key-test');        // Put your Polr API key here
define('POLR_API_QUOTA', 60);                       // Put your Polr API quota here

use Adeelnawaz\PolrApiClient\PolrAPI;
use Adeelnawaz\PolrApiClient\EventSubscriber\LinkDeserealizeSubscriber;

error_reporting(E_ALL);

$autoloader = require __DIR__ . '/../vendor/autoload.php';
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($autoloader, "loadClass"));

$serializer = JMS\Serializer\SerializerBuilder::create()
    // Note: in order to use lookupLink API, this subscriber must be added
    ->configureListeners(function (JMS\Serializer\EventDispatcher\EventDispatcher $dispatcher) {
        $dispatcher->addSubscriber(new LinkDeserealizeSubscriber());
    })
    ->build();

$api = new PolrAPI(
    POLR_API_URL,
    POLR_API_KEY,
    POLR_API_QUOTA,
    $serializer
);