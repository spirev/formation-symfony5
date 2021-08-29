<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Form\CartConfirmationType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var CartService
     */
    protected $cartService;

    public function __construct(ProductRepository $productRepository, CartService $cartService)
    {
        $this->productRepository = $productRepository;
        $this->cartService = $cartService;
    }

    /**
     * @Route("/cart", name="cart_show")
     */
    public function show() {

        $form = $this->createForm(CartConfirmationType::class);

        return $this->render('cart/index.html.twig', [
            'items' => $this->cartService->getDetailedCartItems(),
            'totalPrice' => $this->cartService->getTotal(),
            'confirmationForm' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id":"\d+"})
     */
    public function add($id, Request $request) {
        
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit n'éxiste pas");
        }

        $this->cartService->add($id);

        if ($request->query->get('returnToCart')) {
            return $this->redirectToRoute("cart_show");
        }

        return $this->redirectToRoute('product_show', [
            'product_slug' => $product->getSlug(),
            'category_slug' => $product->getCategory()->getSlug()
        ]);
    }

    /**
     * @Route("/cart/decrement/{id}", name="cart_decrement", requirements={"id": "\d+"})
     */
    public function decrement($id) {

        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Ce produit n'éxiste pas et ne peut donc pas être supprimé");
        }

        $this->cartService->decrement($id);

        return $this->redirectToRoute("cart_show");
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete", requirements={"id": "\d+"})
     */
    public function delete($id) {

        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Ce produit n'éxiste pas et ne peut donc pas être supprimé");
        }

        $this->cartService->remove($id);

        $this->addFlash('success', "La totalité de ce produit à été supprimé de votre panier");

        return $this->redirectToRoute("cart_show");
    }
}
