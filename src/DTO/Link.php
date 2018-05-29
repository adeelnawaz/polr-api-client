<?php

namespace Adeelnawaz\PolrApiClient\DTO;


use JMS\Serializer\Annotation as Serializer;

class Link
{
    /**
     * The URL to shorten
     * @var string
     * @Serializer\Type("string")
     */
    protected $url;

    /**
     * Whether the URL should be a secret URL or not. Defaults to false
     * @var bool
     * @Serializer\Type("bool")
     */
    protected $isSecret = false;

    /**
     * A custom ending for the short URL. If left empty, no custom ending will be assigned
     * @var string
     * @Serializer\Type("string")
     */
    protected $customEnding;

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Link
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSecret()
    {
        return $this->isSecret;
    }

    /**
     * @param bool $isSecret
     * @return Link
     */
    public function setIsSecret($isSecret)
    {
        $this->isSecret = $isSecret;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomEnding()
    {
        return $this->customEnding;
    }

    /**
     * @param string $customEnding
     * @return Link
     */
    public function setCustomEnding($customEnding)
    {
        $this->customEnding = $customEnding;
        return $this;
    }


}