<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

class ProductController extends AbstractController
{
    #[Route('/add/product', name: 'addproduct')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {

        if ($request->isMethod('POST')) {
            $product = new product();
            $product->setName($request->request->get('name'));
            $product->setLibelle($request->request->get('libelle'));
            $product->setPrice($request->request->get('price'));
            $product->setDescription($request->request->get('description'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
        }
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
}
