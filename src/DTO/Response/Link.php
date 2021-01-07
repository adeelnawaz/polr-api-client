<?php

namespace Adeelnawaz\PolrApiClient\DTO\Response;

use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;

class Link
{
    /**
     * @var string
     * @Groups("Default")
     */
    private $longUrl;

    /**
     * @var string
     * @Groups("Default")
     */
    private $shortUrl;

    /**
     * @var DateTime|null
     * @Groups("Default")
     */
    private $createdAt;

    /**
     * @var int
     * @Groups("Default")
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
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
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