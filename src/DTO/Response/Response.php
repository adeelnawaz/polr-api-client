<?php

namespace Adeelnawaz\PolrApiClient\DTO\Response;


class Response
{
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';
    const MESSAGE_ERROR_DEFAULT = 'Something went wrong.';

    const HTTP_NOT_FOUND = 404;
    const HTTP_CONFLICT = 409;
    const HTTP_UNAUTHORIZE = 401;
    const HTTP_INTERNAL_ERROR = 500;
    const HTTP_UNPROCESSABLE_ENTITY = 422;
    const HTTP_CREATED = 201;
    const HTTP_OK = 200;

    const ERR_INVALID_JSON = 100;
    const ERR_QUOTA_EXCEEDED = 429;

    /**
     * @var string success|error
     */
    protected $status;

    /**
     * @var int the API response code
     */
    protected $code;

    /**
     * @var string the API response code message
     */
    protected $errorCode;

    /**
     * @var string the response message
     */
    protected $message;

    /**
     * @var object|null the response data
     */
    protected $data;

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return Response
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     * @return Response
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param string $errorCode
     * @return Response
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return Response
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Returns data object or a requested property of it
     * @param string $prop1 the property of data object to fetch
     * @param string $prop2 the property of data->$prop1 (nested) to fetch
     * @param string $propX ...
     * @return false|mixed if given prop is not found at any level, false is returned
     */
    public function getData()
    {
        $props = func_get_args();

        if (empty($props)) {
            return $this->data;
        }

        $return = $this->data;
        foreach ($props as $prop) {
            if (!isset($return->{$prop})) {
                return false;
            }
            $return = $return->{$prop};
        }

        return $return;
    }

    /**
     * Returns json_formated data object or a requested property of it
     * @param string $prop1 the property of data object to fetch
     * @param string $prop2 the property of data->$prop1 (nested) to fetch
     * @param string $propX ...
     * @return false|mixed if given prop is not found at any level, false is returned
     */
    public function getJsonData()
    {
        $data = call_user_func_array([$this, 'getData'], func_get_args());

        return json_encode($data);
    }

    /**
     * @param null|object $data
     * @return Response
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Returns response with default values
     *
     * @return Response
     */
    public static function getDefaultResponse()
    {
        return (new self)
            ->setStatus(self::STATUS_ERROR)
            ->setCode(self::HTTP_INTERNAL_ERROR)
            ->setMessage(self::MESSAGE_ERROR_DEFAULT);
    }

    /**
     * Returns true if self is a success response
     * @return bool
     */
    public function isSuccessResponse()
    {
        return $this->status === self::STATUS_SUCCESS;
    }
}