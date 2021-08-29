<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Cart\CartService;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchaseConfirmationController extends AbstractController{

    public function __construct()
    {
        
    }

    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour passer une commande")
     */
    public function confirm(Request $request, CartService $cartService, EntityManagerInterface $manager) {

        $form = $this->createForm(CartConfirmationType::class);
        
        $form->handleRequest($request);

        if (count($cartService->getDetailedCartItems()) === 0) {
            $this->addFlash('warning', 'Vous devez ajouter au moins un produit à votre panier avant de pouvoir le valider');
            return $this->redirectToRoute("cart_show");
        }

        if (!$form->isSubmitted()) {
            $this->addFlash('warning', 'Vous devez remplir le formulaire pour valider votre commande');
            return $this->redirectToRoute("cart_show");
        }

        /**
         * @var Purchase
         */
        $purchase = $form->getData();
        $purchase->setUser($this->getUser());
        $purchase->setPurchasedAt(new DateTime());
        $purchase->setTotal($cartService->getTotal() * 100);

        foreach ($cartService->getDetailedCartItems() as $cartItem) {
            $purchaseItem = new PurchaseItem;
            $purchaseItem->setPurchase($purchase)
            ->setProduct($cartItem->product)
            ->setProductName($cartItem->product->getName())
            ->setProductPrice($cartItem->product->getPrice())
            ->setQuantity($cartItem->qty)
            ->setTotal($cartItem->getTotal() * 100);
            
            $manager->persist($purchaseItem);
            
        }
        
        $manager->persist($purchase);
        $manager->flush();

        $cartService->empty();

        $this->addFlash('success', 'votre commande a bien été enregistrée !');

        return $this->redirectToRoute('homepage');
    }
}