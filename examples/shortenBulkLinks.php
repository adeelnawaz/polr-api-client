<?php

/**
 * Usage:
 * POLR_API_URL=http://polr-api.test POLR_API_KEY=polr-api-key-test POLR_API_QUOTA=60 php shortenBulkLinks.php
 */

use Adeelnawaz\PolrApiClient\DTO;
use Adeelnawaz\PolrApiClient\Exception\ApiResponseException;

require_once __DIR__ . '/_prepend.inc';

$link1 = new DTO\Link();
$link1->setUrl('https://www.google.com/search?tbm=isch&source=hp&biw=1863&bih=916&ei=IksNW5eLHqzisAfvgKKQBg&q=samurai+jack&oq=samurai+jack&gs_l=img.3..0l10.799.2671.0.2891.13.10.0.3.3.0.54.372.9.9.0....0...1ac.1.64.img..1.12.380.0...0.NlHgI6Y6mmY')
    ->setIsSecret(false);

$link2 = new DTO\Link();
$link2->setUrl('https://www.google.com/search?safe=off&tbm=isch&source=hp&biw=1863&bih=965&ei=NUsNW6TuK5LekgW18J3IBw&q=herobrine&oq=herobrine&gs_l=img.3..0l10.12240.22796.0.22926.20.13.0.7.7.0.47.449.11.11.0....0...1ac.1.64.img..2.18.469.0...0.FQWfO5OhTxU')
    ->setIsSecret(true)
    ->setCustomEnding('herobrine1')
;

try {
    $responseLinks = $api->shortenBulkLinks([$link1, $link2]);
    print_r($responseLinks);
} catch (ApiResponseException $e) {
    echo "Error: ({$e->getCode()} - {$e->getErrorCode()}) \"{$e->getMessage()}\"\n";
}