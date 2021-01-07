<?php

/**
 * Usage:
 * POLR_API_URL=http://polr-api.test POLR_API_KEY=polr-api-key-test POLR_API_QUOTA=60 php lookupLink.php
 */

use Adeelnawaz\PolrApiClient\Exception\ApiResponseException;

require_once __DIR__ . '/_prepend.inc';

try {
    $responseLink1 = $api->lookupLink('4tlfh');
    print_r($responseLink1);
} catch (ApiResponseException $e) {
    echo "Error: ({$e->getCode()} - {$e->getErrorCode()}) \"{$e->getMessage()}\"\n";
}

try {
    $responseLink2 = $api->lookupLink('herobrine1', '33e0');
    print_r($responseLink2);
} catch (ApiResponseException $e) {
    echo "Error: ({$e->getCode()} - {$e->getErrorCode()}) \"{$e->getMessage()}\"\n";
}