<?php

namespace Adeelnawaz\PolrApiClient\EventSubscriber;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;
use Adeelnawaz\PolrApiClient\DTO;

class LinkDeserealizeSubscriber implements EventSubscriberInterface
{
    const DATE_FORMAT = 'Y-m-d H:i:s.u';

    public static function getSubscribedEvents()
    {
        return array(
            array(
                'event' => 'serializer.pre_deserialize',
                'method' => 'onPreDeserialize',
                'class' => DTO\Response\Link::class,
                'format' => 'json'
            ),
        );
    }

    public function onPreDeserialize(PreDeserializeEvent $event)
    {
        $data = $event->getData();
        $date = \DateTime::createFromFormat(
            self::DATE_FORMAT,
            $data['created_at']['date'],
            new \DateTimeZone($data['created_at']['timezone'])
        );
        $data['created_at'] = $date->format('Y-m-d\TH:i:sO');
        $event->setData($data);
    }
}