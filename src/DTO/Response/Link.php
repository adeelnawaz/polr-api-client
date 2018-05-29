<?php

namespace Adeelnawaz\PolrApiClient\DTO\Response;

use JMS\Serializer\Annotation as Serializer;

class Link
{
    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $longUrl;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $shortUrl;

    /**
     * @var DateTime
     * @Serializer\Type("DateTime")
     */
    private $createdAt;

    /**
     * @var int
     * @Serializer\Type("int")
     */
    private $clicks;

    /**
     * @param string $longUrl
     * @return Link
     */
    public function setLongUrl($longUrl)
    {
        $this->longUrl = $longUrl;
        return $this;
    }

    /**
     * @param string $shortUrl
     * @return Link
     */
    public function setShortUrl($shortUrl)
    {
        $this->shortUrl = $shortUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getLongUrl()
    {
        return $this->longUrl;
    }

    /**
     * @return string
     * @Serializer\Type("string")
     */
    public function getShortUrl()
    {
        return $this->shortUrl;
    }

    /**
     * @return array
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime|array $createdAt
     * @return Link
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return int
     */
    public function getClicks()
    {
        return $this->clicks;
    }

    /**
     * @param int $clicks
     * @return Link
     */
    public function setClicks($clicks)
    {
        $this->clicks = $clicks;
        return $this;
    }
}