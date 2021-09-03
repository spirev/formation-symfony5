<?php

namespace App\EventDispatcher;

use App\Event\ProductViewEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductViewSubscriber implements EventSubscriberInterface{

    protected $logger;

    public function __construct(LoggerInterface $loggerInterface)
    {
        $this->logger = $loggerInterface;
    }

    public static function getSubscribedEvents()
    {
        return [
            'product_view' => 'productView'
        ];
    }

    public function productView(ProductViewEvent $productViewEvent) {
        $this->logger->info("Vous etes sur la page du produit n° " . $productViewEvent->getProduct()->getId());
    }

}