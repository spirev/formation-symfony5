<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Cart\CartService;
use App\Event\PurchaseSuccessEvent;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PurchasePaymentSuccessController extends AbstractController {

    /**
     * @Route("/purchase/terminate/{id}", name="purchase_payment_success")
     * @IsGranted("ROLE_USER")
     */
    public function success(PurchaseRepository $purchaseRepository, $id, EntityManagerInterface $em, CartService $cartService, EventDispatcherInterface $dispatcher) {

        /**
         * @var Purchase
         */
        $purchase = $purchaseRepository->find($id);

        if (!$purchase
         || ($purchase && $purchase->getUser() !== $this->getUser() )
         || ($purchase && $purchase->getStatus() === Purchase::STATUS_PAID)) {
            $this->addFlash('warning', "La commande n'éxiste pas");
            $this->redirectToRoute("purchase_index");
        }

        // ATTENTION ( DOUBLE ALERT !!!) si un utilisateur a validé sa commande alors au moment de payer il peut accéder a cette route (si il la connait) via l'url et valider son payment sans payer !
        // Voir les webhook sur strip
        // dans l'idée on supprime les deux prochaine lignes ( setStatus + flush) et on laisse stripe le faire via un webhook sur un endpoint
        $purchase->setStatus(Purchase::STATUS_PAID);

        $em->flush();

        $cartService->empty();

        // Lancement d'un évenement pour permettre aux developpeurs de réagir à la prise de commande
        $purchaseEvent = new PurchaseSuccessEvent($purchase); 
        $dispatcher->dispatch($purchaseEvent, 'purchase_success');

        $this->addFlash('success', "La commande a bien été payé !");
        return $this->redirectToRoute('purchase_index');
    }
}