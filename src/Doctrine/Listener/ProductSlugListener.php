<?php

namespace App\Doctrine\Listener;

use App\Entity\Product;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductSlugListener {

    protected $slugger;

    public function __construct(SluggerInterface $sluggerInterface, LoggerInterface $loggerInterface)
    {
        $this->slugger = $sluggerInterface;
    }

    public function prePersist(Product $entity, LifecycleEventArgs $event) {

        // utilisation de doctrine listener ($entity) donc plus besoin de préciser sur quel objet on travail

        // $entity = $event->getObject();

        // if (!$entity instanceof Product) {
        //     return;
        // }

        if (empty($entity->getSlug())) {
            $entity->setSlug(strtolower($this->slugger->slug($entity->getName())));
        }
    }
}