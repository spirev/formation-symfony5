<?php

namespace App\Controller;

use App\Taxes\Calculator;
use App\Taxes\Detector;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class TestController extends AbstractController{

    /**
     * @Route("/bob", name="hello")
     */
    public function hello(){
        
        return new Response("hello");
    }

    /**
     * @Route("/bob", name="home")
     */
    public function home(){
    
        return new Response("home");
    }
}