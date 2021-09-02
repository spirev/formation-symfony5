<?php

namespace App\EventDispatcher;

use App\Event\PurchaseSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents()
    {
        return [
            'purchase_success' => 'sendSuccessEmail' 
        ];
    }

    public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent) {
        dd($purchaseSuccessEvent);
    }
}