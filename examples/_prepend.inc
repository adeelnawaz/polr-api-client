<?php

define('POLR_API_URL', 'http://polr-api.test');     // Put your Polr API URL here
define('POLR_API_KEY', 'polr-api-key-test');        // Put your Polr API key here
define('POLR_API_QUOTA', 60);                       // Put your Polr API quota here

use Adeelnawaz\PolrApiClient\PolrApi;
use Adeelnawaz\PolrApiClient\EventSubscriber\LinkDeserealizeSubscriber;

error_reporting(E_ALL);

// Register composer spl autoload
$autoloader = require __DIR__ . '/../vendor/autoload.php';

// Register doctrine annotation autoload for JMS to work properly
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($autoloader, "loadClass"));

// Build JMS Serializer
$serializer = JMS\Serializer\SerializerBuilder::create()
    // Note: in order to use lookupLink API, this subscriber must be added
    ->configureListeners(function (JMS\Serializer\EventDispatcher\EventDispatcher $dispatcher) {
        $dispatcher->addSubscriber(new LinkDeserealizeSubscriber());
    })
    ->build();

// Instantiate PolrApi object
$api = new PolrApi(
    POLR_API_URL,
    POLR_API_KEY,
    POLR_API_QUOTA,
    $serializer
);