<?php

namespace App\Purchase;

use App\Cart\CartService;
use DateTime;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasePersister extends AbstractController{

    protected $cartService;
    protected $manager;

    public function __construct(CartService $cartService, EntityManagerInterface $manager)
    {
        $this->cartService = $cartService;
        $this->manager = $manager;
    }

    public function storePurchase(Purchase $purchase) {

        $purchase->setUser($this->getUser());
        $purchase->setTotal($this->cartService->getTotal() * 100);

        foreach ($this->cartService->getDetailedCartItems() as $cartItem) {
            $purchaseItem = new PurchaseItem;
            $purchaseItem->setPurchase($purchase)
            ->setProduct($cartItem->product)
            ->setProductName($cartItem->product->getName())
            ->setProductPrice($cartItem->product->getPrice())
            ->setQuantity($cartItem->qty)
            ->setTotal($cartItem->getTotal() * 100);
            
            $this->manager->persist($purchaseItem);
            
        }

        $this->manager->persist($purchase);
        $this->manager->flush();
    }
}