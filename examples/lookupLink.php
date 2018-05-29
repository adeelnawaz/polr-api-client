<?php

use Adeelnawaz\PolrApiClient\Exception\ApiResponseException;

require_once __DIR__ . '/_prepend.inc';

try {
    $responseLink1 = $api->lookupLink('f');
    $responseLink2 = $api->lookupLink('herobrine1', '33e0');

    print_r($responseLink1);
    print_r($responseLink2);
} catch (ApiResponseException $e) {
    echo "Error: ({$e->getCode()} - {$e->getErrorCode()}) \"{$e->getMessage()}\"\n";
}