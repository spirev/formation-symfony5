<?php

namespace App\EventDispatcher;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class PrenomSubscriber implements EventSubscriberInterface{

    public static function getSubscribedEvents()
    {
        return [
            // 'kernel.request' => 'addPrenomAttributes'
        ];
    }

    public function AddPrenomAttributes(RequestEvent $requestEvent) {

        $requestEvent->getRequest()->attributes->set('prenom', 'lior');
    }
}