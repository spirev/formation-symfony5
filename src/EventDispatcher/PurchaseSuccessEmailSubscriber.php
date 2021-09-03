<?php

namespace App\EventDispatcher;

use App\Event\PurchaseSuccessEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface {

    protected $mailer;

    public function __construct(MailerInterface $mailerInterface)
    {
        $this->mailer = $mailerInterface;
    }

    public static function getSubscribedEvents()
    {
        return [
            'purchase_success' => 'sendSuccessEmail' 
        ];
    }

    public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent) {

        $user = $purchaseSuccessEvent->getPurchase()->getUser();
        $purchase = $purchaseSuccessEvent->getPurchase();

        $mail = new TemplatedEmail();
        $mail->to(new Address($user->getEmail(), $user->getFullName()))
        ->from("admin@mail.com")
        ->subject("Votre commande n°" . $purchase->getId() . "a bien été confirmé !")
        ->htmlTemplate('emails/purchase_success.html.twig')
        ->context([
            'purchase' => $purchase,
            'user' => $user
        ]);

        $this->mailer->send($mail);
    }
}