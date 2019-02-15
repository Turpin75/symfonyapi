<?php

namespace App\EventSubscriber;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\ObjectEvent;

class postSerializeSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => Events::POST_SERIALIZE,
                'format' => 'json',
                'class' => 'App\Entity\Article',
                'method' => 'onPostSerialize'
            ],

        ];
    }

    public static function onPostSerialize(ObjectEvent $event)
    {
        // Possibilité de récupérer l'object qui a été sérialisé
        $object = $event->getObject();

        $date = new \DateTime();

        // Possibilité de modifier le tableau aprés sérialisation
        $event->getVisitor()->addData('delivered_at', $date->format('l jS \of F Y h:i:s A'));
    }
}