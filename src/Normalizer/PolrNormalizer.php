<?php

namespace Adeelnawaz\PolrApiClient\Normalizer;

use Adeelnawaz\PolrApiClient\DTO\Response\Link;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class PolrDenormalizer
 * @package Adeelnawaz\PolrApiClient\Deserializer
 */
class PolrNormalizer extends ObjectNormalizer
{
    protected function prepareForDenormalization($data)
    {
        $data = parent::prepareForDenormalization($data);

        $this->prepareDateAttrib('created_at', $data);
        $this->prepareDateAttrib('updated_at', $data);

        return $data;
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return Link::class === $type;
    }

    /**
     * Converts Polr's bizzare array into ISO8601 datetime string
     * @param $attrib
     * @param $data
     */
    private function prepareDateAttrib($attrib, &$data)
    {
        if (!isset($data[$attrib])) {
            return;
        }

        $data[$attrib] = \DateTime::createFromFormat(
            'Y-m-d H:i:s.u',
            $data[$attrib]['date'],
            new \DateTimeZone($data[$attrib]['timezone'])
        );
    }
}