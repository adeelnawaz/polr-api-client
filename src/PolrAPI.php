<?php

namespace Adeelnawaz\PolrApiClient;

use Adeelnawaz\PolrApiClient\DTO;
use Adeelnawaz\PolrApiClient\Exception\ApiResponseException;
use Adeelnawaz\PolrApiClient\Service\Curl;
use JMS\Serializer\Serializer;

/**
 * This abstract class is responsible for holding global configurations and managing commonly used features.
 *
 * Class CoreAPI
 * @package Adeelnawaz\PolrApiClient
 */
class PolrAPI
{
    const ENDPOINT_ACTION_SHORTEN = '/api/v2/action/shorten';
    const ENDPOINT_ACTION_SHORTEN_BULK = '/api/v2/action/shorten_bulk';
    const ENDPOINT_ACTION_LOOKUP = '/api/v2/action/lookup';
    const ENDPOINT_DATA_LINK = '/api/v2/data/link';

    /**
     * @var string API URL
     */
    private $url;

    /**
     * @var string API key
     */
    private $key;

    /**
     * @var float delay in seconds between two requests
     */
    private $timeDelay = 0;

    /**
     * @var float microtime of last call to Polr API
     */
    private static $lastCallTime;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * PolrAPI constructor.
     * @param string $url the Polr API URL
     * @param string $key the Polr API secret key
     * @param int $quota the request/minute quota
     * @param Serializer $serializer
     */
    public function __construct(string $url, string $key, int $quota, Serializer $serializer)
    {
        $this->url = $url;
        $this->key = $key;
        $this->setTimeDelayByQuota($quota);
        $this->serializer = $serializer;
    }

    /**
     * Shortens a single link
     * @param DTO\Link $link
     * @return DTO\Response\Link
     * @throws ApiResponseException
     */
    public function shortenLink(DTO\Link $link)
    {
        $response = $this->callAPIEndpoint(
            self::ENDPOINT_ACTION_SHORTEN,
            $this->serializer->serialize($link, 'json')
        );

        $responseLink = new DTO\Response\Link();
        $responseLink->setLongUrl($link->getUrl())
            ->setShortUrl($response->getData('result'));

        return $responseLink;
    }

    /**
     * Shortens an array of links in bulk
     * @param Link[] $links
     * @return DTO\Response\Link[]
     * @throws ApiResponseException
     */
    public function shortenBulkLinks(array $links)
    {
        $response = $this->callAPIEndpoint(
            self::ENDPOINT_ACTION_SHORTEN_BULK,
            ['data' => $this->serializer->serialize(['links' => $links], 'json')]
        );

        $result = $response->getJsonData('result', 'shortened_links');

        if ($result === false) {
            $result = '[]';
        }

        return $this->serializer->deserialize($result, 'array<' . DTO\Response\Link::class . '>', 'json');
    }

    /**
     * Looks up link with provided URL ending and URL key
     * @param string $urlEnding the URL ending
     * @param string $urlKey URL key for secret URL - ignored if not secret URL
     * @return DTO\Response\Link
     * @throws ApiResponseException
     */
    public function lookupLink(string $urlEnding, string $urlKey = null)
    {
        $response = $this->callAPIEndpoint(
            self::ENDPOINT_ACTION_LOOKUP, [
            'url_ending' => $urlEnding,
            'url_key' => $urlKey
        ]);

        $responseLink = $this->serializer->deserialize($response->getJsonData('result'), DTO\Response\Link::class, 'json');
        $responseLink->setShortUrl($this->url . '/' . $urlEnding . (!empty($urlKey) ? '/' . $urlKey : ''));

        return $responseLink;
    }

    /**
     * Calls the specified Polr API endpoint
     * @param string $endpoint API endpoint - should be one of self::ENDPOINT_*
     * @param string $data JSON encoded payload
     * @return DTO\Response\Response
     * @throws ApiResponseException
     */
    private function callAPIEndpoint($endpoint, $data)
    {
        if (!in_array($endpoint, [
            self::ENDPOINT_ACTION_SHORTEN,
            self::ENDPOINT_ACTION_SHORTEN_BULK,
            self::ENDPOINT_ACTION_LOOKUP,
            self::ENDPOINT_DATA_LINK
        ], true)) {
            throw new \InvalidArgumentException('Invalid API endpoint: ' . $endpoint);
        }

        $this->throttleRequests();

        $endpointUrl = $this->url . $endpoint . '?response_type=json&key=' . $this->key;

        if (!is_string($data)) {
            $data = json_encode($data);
        }

        /* Make a curl request */
        $curl = new Curl();

        $response = $curl->doCurl($endpointUrl, 'POST', $data, 'application/json');

        if (!$response->isSuccessResponse()) {
            $this->handleErrorResponse($response);
        }

        return $response;
    }

    private function throttleRequests()
    {
        if ($this->timeDelay !== 0) {
            /*
             * Calculate idle time from timeDelay
             * nextReqTime = lastCallTime + timeDelay
             * idleTime = nextReqTime - currTime
             */
            $idleMicrosec = 1000000 * (self::$lastCallTime + $this->timeDelay - microtime(true));

            if ($idleMicrosec > 0) {
                usleep($idleMicrosec);
            }
        }

        self::$lastCallTime = microtime(true);
    }

    /**
     * Gets quota in req/min and saves time delay between two requests in sec (round up to milisec)
     * @param int $quota
     */
    private function setTimeDelayByQuota(int $quota)
    {
        $this->timeDelay = ($quota === 0 ? 0 : round(60 / $quota, 6));
    }

    /**
     * @param DTO\Response\Response $response
     * @throws ApiResponseException
     */
    private function handleErrorResponse(DTO\Response\Response $response)
    {
        throw new ApiResponseException($response->getMessage(), $response->getCode(), $response->getErrorCode());
    }
}
