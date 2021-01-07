<?php

/**
 * Usage:
 * POLR_API_URL=http://polr-api.test POLR_API_KEY=polr-api-key-test POLR_API_QUOTA=60 php shortenLink.php
 */
use Adeelnawaz\PolrApiClient\DTO;
use Adeelnawaz\PolrApiClient\Exception\ApiResponseException;

require_once __DIR__ . '/_prepend.inc';

$link = new DTO\Link();
$link->setUrl('https://www.google.com/search?tbm=isch&source=hp&biw=1863&bih=916&ei=IksNW5eLHqzisAfvgKKQBg&q=samurai+jack&oq=samurai+jack&gs_l=img.3..0l10.799.2671.0.2891.13.10.0.3.3.0.54.372.9.9.0....0...1ac.1.64.img..1.12.380.0...0.NlHgI6Y6mmY')
    ->setIsSecret(true);

try {
    $responseLink = $api->shortenLink($link);

    print_r($responseLink);
} catch (ApiResponseException $e) {
    echo "Error: ({$e->getCode()} - {$e->getErrorCode()}) \"{$e->getMessage()}\"\n";
}