<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ShoppingCartItem;
use App\Repository\ProductRepository;
use App\Repository\ShoppingCartItemRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ShoppingCartController extends AbstractController
{
    #[Route('/shopping/cart', name: 'app_shopping_cart')]
    public function index(): Response
    {
        return $this->render('shopping_cart/index.html.twig', [
            'controller_name' => 'ShoppingCartController',
        ]);
    }
    #[Route('/addToCart/{productId}', name: 'addToCart')]

    public function addToCart(Request $request, UserRepository $userRepository, ProductRepository $productRepository, $productId): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Get the current session
        // $session = $request->getSession();
        // if (!$session->isStarted()) {
        //     $session->start();
        // }

        // // get the email of the authenticated user
        // $email = $session->get('_security.last_username');
        // if (!$email) {
        //     return new Response('where email', Response::HTTP_BAD_REQUEST);
        // }
        // //find the user instance in the database
        // $user = $userRepository->findOneBy(['email' => $email]);
        $user = $this->getUser();
        if (!$user) {
            return new Response('Utilisateur introuvable', Response::HTTP_BAD_REQUEST);
        }


        $product = $productRepository->find($productId);
        if (!$product) {
            return new Response("Ce produit n'existe pas", Response::HTTP_BAD_REQUEST);
        }
        // Check if the user already has the product in their cart
        $existingCartItem = $entityManager->getRepository(ShoppingCartItem::class)
            ->findOneBy([
                'user' => $user,
                'product' => $product
            ]);

        if ($existingCartItem) {
            // If the user already has the product in their cart, update the quantity
            $existingCartItem->setQuantity($existingCartItem->getQuantity() + 1);
        } else {
            // If the user does not have the product in their cart, create a new cart item
            $cartItem = new ShoppingCartItem();
            $cartItem->setUser($user);
            $cartItem->setProduct($product);
            $cartItem->setQuantity(1);

            $entityManager->persist($cartItem);
        }
        $entityManager->flush();

        // return $this->redirectToRoute('product_show', ['id' => $product->getId()]);
        return new Response('Produit ajouté avec success');
    }
    #[Route('/cart', name: 'cart')]

    public function cart(Request $request, UserRepository $userRepository, ShoppingCartItemRepository $shoppingCartRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Get the current session
        // $session = $request->getSession();
        // if (!$session->isStarted()) {
        //     $session->start();
        // }

        // // get the email of the authenticated user
        // $email = $session->get('_security.last_username');
        // if (!$email) {
        //     return new Response('where email', Response::HTTP_BAD_REQUEST);
        // }
        // $user = $userRepository->findOneBy(['email' => $email]);
        $user = $this->getUser();

        if (!$user) {
            return new Response('Utilisateur introuvable', Response::HTTP_BAD_REQUEST);
        }
        $cartItems = $shoppingCartRepository->findBy(['user' => $user]);

        return $this->render("shopping_cart/cart.html.twig", array("cartItems" => $cartItems));
    }
    #[Route('/removeItem/{id}', name: 'removeItem')]

    public function removeItem(ManagerRegistry $doctrine, ShoppingCartItemRepository $shoppingCartRepository, $id,): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return new Response('Utilisateur introuvable', Response::HTTP_BAD_REQUEST);
        }
        $product = $shoppingCartRepository->find($id);
        $em = $doctrine->getManager();
        $em->remove($product);
        $em->flush();



        return $this->redirectToRoute('cart');
    }
    #[Route('/api/cart', name: 'cartApi')]

    public function cartApi(Request $request, UserRepository $userRepository, ShoppingCartItemRepository $shoppingCartRepository, NormalizerInterface $normalizer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Get the current session
        $user = $this->getUser();

        if (!$user) {
            return new Response('Utilisateur introuvable', Response::HTTP_BAD_REQUEST);
        }
        $cartItems = $shoppingCartRepository->findBy(['user' => $user]);
        $json = $normalizer->normalize($cartItems, 'json', ['groups' => 'cartProducts']);
        return new Response(json_encode($json));
    }
}
