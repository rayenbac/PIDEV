<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CategoryApiController extends AbstractController
{
    #[Route('/category/api', name: 'app_category_api')]
    public function index(): Response
    {
        return $this->render('category_api/index.html.twig', [
            'controller_name' => 'CategoryApiController',
        ]);
    }
    #[Route('/api/getCategories', name: 'getCategoriesApi')]
    public function getProductsApi(CategoryRepository $categoryRepository, NormalizerInterface $normalizer): Response
    {
        $categories = $categoryRepository->findAll();
        $normalizedCategories = $normalizer->normalize($categories, 'json', ['groups' => 'userProducts']);
        $json = json_encode($normalizedCategories);
        return new Response($json);
    }
    #[Route('/api/addCategory', name: 'addCategoryApi')]
    public function addCategoryApi(NormalizerInterface $normalizer, ManagerRegistry $doctrine, Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();
        $currenttime = new DateTime();
        $category->setCreateAt($currenttime);
        $category->setUpdatedAt($currenttime);
        $category->setName($request->get('name'));
        $category->setDescription($request->get('description'));

        $em = $doctrine->getManager();
        $em->persist($category);
        $em->flush();
        $json = $normalizer->normalize($category, 'json', ['groups' => 'userProducts']);
        return new Response(json_encode($json));
    }
    #[Route('/api/updateCategory/{id}', name: 'updateCategoryApi')]
    public function updateProductsApi(NormalizerInterface $normalizer, ManagerRegistry $doctrine, Request $request, CategoryRepository $categoryRepository, $id): Response
    {
        $category = $categoryRepository->find($id);
        $form = $this->createForm(categoryType::class, $category);
        $currenttime = new DateTime();
        $category->setCreateAt($currenttime);
        $category->setUpdatedAt($currenttime);
        $category->setName($request->get('name'));
        $category->setDescription($request->get('description'));

        $em = $doctrine->getManager();
        $em->persist($category);
        $em->flush();
        $json = $normalizer->normalize($category, 'json', ['groups' => 'userProducts']);
        return new Response(json_encode($json));
    }
    #[Route('/api/deleteProduct/{id}', name: 'deleteProductApi')]
    public function deleteProductsApi(NormalizerInterface $normalizer, ManagerRegistry $doctrine, CategoryRepository $categoryRepository, $id): Response
    {
        $category = $categoryRepository->find($id);
        $em = $doctrine->getManager();
        $em->remove($category);
        $em->flush();
        $json = $normalizer->normalize($category, 'json', ['groups' => 'userProducts']);
        return new Response("Categorie supprimé avec success" . json_encode($json));
    }
    #[Route('/api/category/{id}', name: 'CategoryApi')]
    public function getProductApi(NormalizerInterface $normalizer, ManagerRegistry $doctrine, CategoryRepository $categoryRepository, $id): Response
    {
        $category = $categoryRepository->find($id);
        $normalizedCategory = $normalizer->normalize($category, 'json', ['groups' => 'userProducts']);
        $json = json_encode($normalizedCategory);
        return new Response($json);
    }
}
