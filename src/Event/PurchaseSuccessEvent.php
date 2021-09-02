<?php

namespace App\Event;

use App\Entity\Purchase;
use Symfony\Contracts\EventDispatcher\Event;

class PurchaseSuccessEvent extends Event {

    private $purchase;

    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
        dump('IN Purchase Event');
    }

    public function getPurchase() {
        return $this->purchase;
    }
}