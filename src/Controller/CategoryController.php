<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
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

    #[Route('/categories', name: 'categories')]
    public function categories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('category/categories.html.twig', [
            'categories' => $categories
        ]);
    }


    #[Route('/addCategory', name: 'addCategory')]
    public function addCategory(ManagerRegistry $doctrine, Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute("categories");
        }
        return $this->renderForm("category/addCategory.html.twig", array("addCategoryForm" => $form));
    }

    #[Route('/updateCategory/{id}', name: 'updateCategory')]
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
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute("categories");
        }
        return $this->renderForm(
            "category/addCategory.html.twig",
            array("addCategoryForm" => $form)
        );
    }

    #[Route('/deleteCategory/{id}', name: 'deleteCategory')]
    public function deleteCategory(
        $id,
        CategoryRepository $r,
        ManagerRegistry $doctrine
    ): Response {
        $category = $r->find($id);
        if (!empty($category->getProducts())) {
            return new Response("Category cannot be deleted as it still contains products.", Response::HTTP_BAD_REQUEST);
        }
        $em = $doctrine->getManager();
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('categories');
    }
}
