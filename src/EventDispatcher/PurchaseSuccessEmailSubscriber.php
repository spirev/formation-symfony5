<?php

namespace App\EventDispatcher;

use App\Event\PurchaseSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface {

    protected $logger;

    public function __construct(LoggerInterface $loggerInterface)
    {
        $this->logger = $loggerInterface;
    }

    public static function getSubscribedEvents()
    {
        return [
            'purchase_success' => 'sendSuccessEmail' 
        ];
    }

    public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent) {
        $this->logger->info("Un email a été envoyé pour la commande n°" . $purchaseSuccessEvent->getPurchase()->getId());
    }
}