<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductApiController extends AbstractController
{
    #[Route('/product/api', name: 'app_product_api')]
    public function index(): Response
    {
        return $this->render('product_api/index.html.twig', [
            'controller_name' => 'ProductApiController',
        ]);
    }
    #[Route('/api/getProducts', name: 'getProductsApi')]
    public function getProductsApi(ProductRepository $productsRepository, NormalizerInterface $normalizer): Response
    {
        $products = $productsRepository->findAll();
        $normalizedProducts = $normalizer->normalize($products, 'json', ['groups' => 'userProducts']);
        $json = json_encode($normalizedProducts);
        return new Response($json);
    }
    #[Route('/api/addProduct', name: 'addProductApi', methods: ['POST'])]
    public function addProductsApi(NormalizerInterface $normalizer, ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger, CategoryRepository $categoryRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $currenttime = new DateTime();
        $product->setCreatedAt($currenttime);
        $product->setUpdatedAt($currenttime);
        $product->setName($request->get('name'));
        $product->setDescription($request->get('description'));
        $product->setPrice($request->get('price'));
        $product->setQuantity($request->get('quantity'));
        //upload image to database with a unique name
        $imageFile = $request->get('image')->getData();
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $imageFile->move(
                    $this->getParameter('products_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                return new JsonResponse("erreur", 400);
            }
            $product->setImage($newFilename);
        }
        //get categoryId , find it by id in the repository and set it as the product's category
        $categoryId = $request->get('categoryId');
        $category =  $categoryRepository->find($categoryId);
        $product->setCategory($category);

        $em = $doctrine->getManager();
        $em->persist($product);
        $em->flush();
        $json = $normalizer->normalize($product, 'json', ['groups' => 'userProducts']);
        return new Response(json_encode($json));
    }
    #[Route('/api/updateProduct/{id}', name: 'addProductApi')]
    public function updateProductsApi(NormalizerInterface $normalizer, ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger, CategoryRepository $categoryRepository, ProductRepository $productRepository, $id): Response
    {
        $product = $productRepository->find($id);
        $form = $this->createForm(ProductType::class, $product);
        $currenttime = new DateTime();
        $product->setCreatedAt($currenttime);
        $product->setUpdatedAt($currenttime);
        $product->setName($request->get('name'));
        $product->setDescription($request->get('description'));
        $product->setPrice($request->get('price'));
        $product->setQuantity($request->get('quantity'));
        //upload image to database with a unique name
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $imageFile->move(
                    $this->getParameter('products_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                return new JsonResponse("erreur", 400);
            }
            $product->setImage($newFilename);
        }
        //get categoryId , find it by id in the repository and set it as the product's category
        $categoryId = $request->get('categoryId');
        $category =  $categoryRepository->find($categoryId);
        $product->setCategory($category);

        $em = $doctrine->getManager();
        $em->persist($product);
        $em->flush();
        $json = $normalizer->normalize($product, 'json', ['groups' => 'userProducts']);
        return new Response(json_encode($json));
    }
    #[Route('/api/deleteProduct/{id}', name: 'deleteProductApi')]
    public function deleteProductsApi(NormalizerInterface $normalizer, ManagerRegistry $doctrine, ProductRepository $productRepository, $id): Response
    {
        $product = $productRepository->find($id);
        $em = $doctrine->getManager();
        $em->remove($product);
        $em->flush();
        $json = $normalizer->normalize($product, 'json', ['groups' => 'userProducts']);
        return new Response("Produit supprimé avec success" . json_encode($json));
    }

    #[Route('/api/product/{id}', name: 'ProductApi')]
    public function getProductApi(NormalizerInterface $normalizer, ManagerRegistry $doctrine, ProductRepository $productRepository, $id): Response
    {
        $product = $productRepository->find($id);
        $normalizedProduct = $normalizer->normalize($product, 'json', ['groups' => 'userProducts']);
        $json = json_encode($normalizedProduct);
        return new Response($json);
    }
    #[Route('/dashboard/products/search', name: 'searchProducts', methods: ['POST'])]
    public function searchProducts(Request $request, ProductRepository $productRepository): JsonResponse
    {
        $filter = $request->request->get('filter');
        $searchTerm = $request->request->get('searchTerm');
        $sortCriteria = $request->request->get('sortCriteria');

        $products = $productRepository->search($filter, $searchTerm, $sortCriteria);
        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice(),
                'quantity' => $product->getQuantity(),
                'createdAt' => $product->getCreatedAt()->format('d-m-Y H:i:s'),
                'updatedAt' => $product->getUpdatedAt()->format('d-m-Y H:i:s'),
            ];
        }
        return $this->json($data);
    }
}
