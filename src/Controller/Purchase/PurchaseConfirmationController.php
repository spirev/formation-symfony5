<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Cart\CartService;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use App\Purchase\PurchasePersister as PurchasePurchasePersister;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Purchase\PurchasePersister;

class PurchaseConfirmationController extends AbstractController{

    protected $presister;

    public function __construct(PurchasePersister $purchasePersister)
    {
        $this->presister = $purchasePersister;
    }

    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour passer une commande")
     */
    public function confirm(Request $request, CartService $cartService) {

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

        $this->presister->storePurchase($purchase);

        return $this->redirectToRoute('purchase_payment_form', [
            'id' => $purchase->getId()
        ]);
    }
}