<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    #[Route('/dashboard/categories', name: 'categories')]
    public function categories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('category/categories.html.twig', [
            'categories' => $categories
        ]);
    }


    #[Route('/dashboard/addCategory', name: 'addCategory')]
    public function addCategory(ManagerRegistry $doctrine, Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $currecttime = new DateTime();
            $category->setCreateAt($currecttime);
            $category->setUpdatedAt($currecttime);
            $em = $doctrine->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute("categories");
        }
        return $this->renderForm("category/addCategory.html.twig", array("form" => $form));
    }

    #[Route('/dashboard/updateCategory/{id}', name: 'updateCategory')]
    public function updateCategory(
        CategoryRepository $repository,
        $id,
        ManagerRegistry $doctrine,
        Request $request
    ) {
        $category = $repository->find($id);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $currecttime = new DateTime();
            $category->setUpdatedAt($currecttime);
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute("categories");
        }
        return $this->renderForm(
            "category/addCategory.html.twig",
            array("form" => $form)
        );
    }

    #[Route('/deleteCategory/{id}', name: 'deleteCategory')]
    public function deleteCategory(
        $id,
        CategoryRepository $r,
        ManagerRegistry $doctrine
    ): Response {
        $category = $r->find($id);
        if ($category->getProducts()->count() > 0) {
            $this->addFlash(
                'error',
                'Cette categorie ne peut pas etre supprimé parcequil contient des produits'
            );
            return $this->redirectToRoute('categories');
        }
        $em = $doctrine->getManager();
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('categories');
    }
}
