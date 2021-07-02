<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Faker\Factory;


class HomeController extends AbstractController{

    /**
     * @Route("/", name="homepage")
     */
    public function homepage(ProductRepository $productRepository){

        $products = $productRepository->findBy([], [], 3);
        return $this->render('home.html.twig', ['products' => $products]);
    }

}