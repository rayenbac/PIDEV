<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/dashboard/products', name: 'products')]
    public function dashboardProducts(ProductRepository $productsRepository, PaginatorInterface $paginator, Request $request): Response
    {
        if ($this->isGranted('ROLE_PATIENT')) {
            return $this->redirectToRoute('home');
        }
        if ($this->isGranted('ROLE_MEDECIN')) {
            return $this->redirectToRoute('home');
        }
        $queryBuilder = $productsRepository->createQueryBuilder('p');
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1), // get the page parameter from the URL, defaults to 1
            3 // limit per page
        );

        return $this->render('product/products.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/dashboard/addProduct', name: 'addProduct')]
    public function addProduct(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response
    {
        if ($this->isGranted('ROLE_PATIENT')) {
            return $this->redirectToRoute('home');
        }
        if ($this->isGranted('ROLE_MEDECIN')) {
            return $this->redirectToRoute('home');
        }
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $currenttime = new DateTime();
            $product->setCreatedAt($currenttime);
            $product->setUpdatedAt($currenttime);
            $imageFile = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
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
                    // ... handle exception if something happens during file upload
                }
                $product->setImage($newFilename);
            }
            $em = $doctrine->getManager();
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute("products");
        }
        return $this->renderForm("product/addProduct.html.twig", array("form" => $form));
    }

    #[Route('/dashboard/updateProduct/{id}', name: 'updateProduct')]
    public function updateProduct(
        ProductRepository $repository,
        $id,
        ManagerRegistry $doctrine,
        Request $request,
        SluggerInterface $slugger
    ) {
        if ($this->isGranted('ROLE_PATIENT')) {
            return $this->redirectToRoute('home');
        }
        if ($this->isGranted('ROLE_MEDECIN')) {
            return $this->redirectToRoute('home');
        }
        $product = $repository->find($id);
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $currenttime = new DateTime();
            $product->setUpdatedAt($currenttime);
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('products_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    return new Response("error uploading the image", Response::HTTP_BAD_REQUEST);
                }
                $product->setImage($newFilename);
            }
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute("products");
        }
        return $this->renderForm(
            "product/editProduct.html.twig",
            array("form" => $form)
        );
    }

    #[Route('/dashboard/deleteProduct/{id}', name: 'deleteProduct')]
    public function deleteProduct(
        $id,
        ProductRepository $r,
        ManagerRegistry $doctrine
    ): Response {
        if ($this->isGranted('ROLE_PATIENT')) {
            return $this->redirectToRoute('home');
        }
        if ($this->isGranted('ROLE_MEDECIN')) {
            return $this->redirectToRoute('home');
        }
        $product = $r->find($id);
        $em = $doctrine->getManager();
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('products');
    }
    #[Route('/product/{id}', name: 'productById')]
    public function productById(ProductRepository $productsRepository, $id): Response
    {
        $product = $productsRepository->find($id);
        return $this->render('product/singleProduct.html.twig', [
            'product' => $product
        ]);
    }
    #[Route('/uploadProductsImage', name: 'productById', methods: ['POST'])]
    // public function uploadProductsImage(Request $request, SluggerInterface $slugger, $imageName): Response
    // {
    //     $imageFile = $request->files->get('file');

    //     // this condition is needed because the 'brochure' field is not required
    //     // so the PDF file must be processed only when a file is uploaded
    //     if ($imageFile) {
    //         $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
    //         // this is needed to safely include the file name as part of the URL
    //         $safeFilename = $slugger->slug($originalFilename);
    //         $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

    //         // Move the file to the directory where brochures are stored
    //         try {
    //             $imageFile->move(
    //                 $this->getParameter('products_directory'),
    //                 $newFilename
    //             );
    //             return $this->json([
    //                 'message' => 'Image uploaded successfully',
    //                 'filename' => $newFilename
    //             ], Response::HTTP_OK);
    //         } catch (FileException $e) {
    //             return new Response('Image upload failed', Response::HTTP_BAD_REQUEST);
    //         }
    //     }
    // }

    public function uploadImage(Request $request): Response
    {
        $file = $request->files->get('image');
        $fileName = uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getParameter('products_directory'), $fileName);
        } catch (FileException $e) {
            return new Response('Image upload failed', Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Image uploaded successfully',
            'imageName' => $fileName
        ], JsonResponse::HTTP_OK);
    }
    #[Route('/uploadEventImage', name: 'uploadEventImage', methods: ['POST'])]


    public function uploadImageB(Request $request): Response
    {
        $file = $request->files->get('image');
        $fileName = uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getParameter('evenement_directory'), $fileName);
        } catch (FileException $e) {
            return new Response('Image upload failed', Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Image uploaded successfully',
            'imageName' => $fileName
        ], JsonResponse::HTTP_OK);
    }


    // public function new(Request $request, SluggerInterface $slugger)
    // {
    //     $product = new Product();
    //     $form = $this->createForm(ProductType::class, $product);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         /** @var UploadedFile $brochureFile */
    //         $imageFile = $form->get('image')->getData();

    //         // this condition is needed because the 'brochure' field is not required
    //         // so the PDF file must be processed only when a file is uploaded
    //         if ($imageFile) {
    //             $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
    //             // this is needed to safely include the file name as part of the URL
    //             $safeFilename = $slugger->slug($originalFilename);
    //             $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

    //             // Move the file to the directory where brochures are stored
    //             try {
    //                 $brochureFile->move(
    //                     $this->getParameter('brochures_directory'),
    //                     $newFilename
    //                 );
    //             } catch (FileException $e) {
    //                 // ... handle exception if something happens during file upload
    //             }
    //             $product->setImage($newFilename);
    //         }

    //         // ... persist the $product variable or any other work

    //         return $this->redirectToRoute('app_product_list');
    //     }

    //     return $this->render('product/new.html.twig', [
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/products', name: 'userProducts')]
    public function userProducts(ProductRepository $productsRepository): Response
    {
        $products = $productsRepository->findAll();
        return $this->render('product/userProducts.html.twig', [
            'products' => $products
        ]);
    }
}
