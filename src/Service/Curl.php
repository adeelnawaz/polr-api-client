<?php

namespace Adeelnawaz\PolrApiClient\Service;

use Adeelnawaz\PolrApiClient\DTO\Response\Response;

/**
 * This class is responsible for managing curl requests.
 *
 * Class Curl
 * @package Adeelnawaz\PolrApiClient
 */
class Curl
{
    const CURL_CONNECTION_TIMEOUT = 20;
    const CURL_TIMEOUT = 30;

    /**
     * Sends the curl request to core API.
     *
     * @param $url
     * @param string $method
     * @param string $data
     * @param string $contentType
     * @return Response
     */
    public function doCurl($url, $method = "GET", $data = null, $contentType = null)
    {
        $curlConnectionTimeout = self::CURL_CONNECTION_TIMEOUT;
        $curlTimeout = self::CURL_TIMEOUT;
        $response = Response::getDefaultResponse();

        /* Call to the specified API endpoint */
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, $curlConnectionTimeout);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $curlTimeout);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

            if ($data !== null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

                if ($contentType !== null) {
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: ' . $contentType,
                            'Content-Length: ' . strlen($data))
                    );
                }
            }

            $result = curl_exec($ch);

            /* Check if error then accordingly throw exception with message and error code */
            if (curl_errno($ch)) {
                $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $message = curl_error($ch);

                curl_close($ch);
                throw new \Exception($message, $code);
            }

            curl_close($ch);

            if (null === $resultObj = json_decode($result)) {
                throw new \Exception('Invalid json: ' . $result, 100);
            }

            if (isset($resultObj->error_code)) {
                throw new \Exception($resultObj->error, $resultObj->status_code);
            }

            $response->setStatus(Response::STATUS_SUCCESS);
            $response->setCode(Response::HTTP_OK);
            $response->setData($resultObj);
        } catch (\Exception $exception) {
            $response->setCode($exception->getCode());
            $response->setMessage($exception->getMessage());
            $response->setErrorCode(
                isset($resultObj->error_code) ? $resultObj->error_code : 'CLIENT_UNEXPECTED'
            );
        }

        return $response;
    }
}
