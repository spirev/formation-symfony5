<?php

namespace App\Cart;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService extends AbstractController{

    
    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    protected function getCart(): array {
        return $this->session->get('cart', []);
    }

    protected function setCart($cart) {
        $this->session->set('cart', $cart);
    }

    public function add($id){

        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            $cart[$id] = 0;
        }

        $cart[$id]++;

        $this->setCart($cart);

        $this->addFlash('success', "Le produit a bien été ajouté");
    }

    public function remove($id) {

        $cart = $this->getCart();

        if (array_key_exists($id, $cart)) {

            unset($cart[$id]);
            $this->setCart($cart);
        }

    }

    public function empty() {
        $this->setCart([]);
    }

    public function decrement($id) {

        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            return;
        }

        if ($cart[$id] === 1) {
            $this->remove($id);
            return;
        }

        $cart[$id]--;

        $this->setCart($cart);

    }

    public function getTotal(): float {
        
        $totalPrice = 0;

        foreach ($this->getCart() as $id => $qty){

            if (!$this->productRepository->find($id)) {
                continue;
            }

            $totalPrice += $this->productRepository->find($id)->getPrice() * $qty;
        }

        return $totalPrice / 100;
    }

    /**
     * @return CartItem[]
     */
    public function getDetailedCartItems(): array {

        $panier = [];

        foreach ($this->session->get('cart', []) as $id => $qty){

            if (!$this->productRepository->find($id)) {
                continue;
            }

            // La création d'un 'CartItem' a uniquement pour but d'éviter la division du prix par 100 dans le template
            $panier[] = new CartItem($this->productRepository->find($id), $qty);
        }

        return $panier;
    }
}