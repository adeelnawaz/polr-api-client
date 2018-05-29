<?php


namespace Adeelnawaz\PolrApiClient\Exception;

/**
 * Class ApiResponseException
 * @package Adeelnawaz\PolrApiClient\Exception
 */
class ApiResponseException extends \Exception
{
    /**
     * @var string
     */
    private $errorCode;

    /**
     * ApiResponseException constructor.
     * @param string $message
     * @param int $code
     * @param string|null $errorCode machine friendly short error string
     */
    public function __construct($message, $code = 500, $errorCode = null)
    {
        parent::__construct($message, $code);

        $this->errorCode = $errorCode;
    }

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }
}
