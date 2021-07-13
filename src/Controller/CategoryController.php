<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/category/create", name="category_create")
     */
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, CategoryRepository $categoryRepository): Response
    {
        $category = new Category;

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            
            if (!$categoryRepository->findBy(array('name' => $category->getName()))) {
                $category->setSlug(strtolower($slugger->slug($category->getName())));
    
                $em->persist($category);
                $em->flush();
                return $this->redirectToRoute('homepage');
            }
            return $this->redirectToRoute('category_create');
        }
        
        $form_view = $form->createView();
        return $this->render('category/create.html.twig', [
            'category_form' => $form_view
        ]);
    }

    /**
     * @Route("/admin/category/{id}/edit", name="category_edit")
     */
    public function edit($id, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response {

        $category = $categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException("La categorie n'éxiste pas...");
        }

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $em->flush();
            return $this->redirectToRoute('homepage');
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'formView' => $form->createView()
        ]);
    }
}
