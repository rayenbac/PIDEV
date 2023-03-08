<?php

namespace App\Controller;


use App\Entity\ShoppingCartItem;
use App\Repository\ProductRepository;
use App\Repository\ShoppingCartItemRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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

    public function addToCart(ProductRepository $productRepository, $productId): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        if (!$user) {
            return new Response('Utilisateur introuvable', Response::HTTP_BAD_REQUEST);
        }


        $product = $productRepository->find($productId);
        if (!$product) {
            return new Response("Ce produit n'existe pas", Response::HTTP_BAD_REQUEST);
        }
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

    public function cart(ShoppingCartItemRepository $shoppingCartRepository): Response
    {


        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $cartItems = $shoppingCartRepository->findBy(['user' => $user]);

        return $this->render("shopping_cart/cart.html.twig", array("cartItems" => $cartItems));
    }
    #[Route('/removeItem/{id}', name: 'removeItem')]

    public function removeItem(ManagerRegistry $doctrine, ShoppingCartItemRepository $shoppingCartRepository, $id): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'Utilisateur introuvable'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $product = $shoppingCartRepository->find($id);
        if (!$product) {
            return new JsonResponse(['success' => false, 'message' => 'Produit introuvable'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $em = $doctrine->getManager();
        $em->remove($product);
        $em->flush();

        return new JsonResponse(['success' => true, 'message' => 'Product removed successfully']);
    }

    #[Route('/api/cart/{userId}', name: 'cartApi')]

    public function cartApi(ShoppingCartItemRepository $shoppingCartRepository, UserRepository $userRepository, NormalizerInterface $normalizer, $userId): Response
    {
        // Get the current session
        $user = $userRepository->find($userId);

        if (!$user) {
            return new Response('Utilisateur introuvable', Response::HTTP_BAD_REQUEST);
        }
        $cartItems = $shoppingCartRepository->findBy(['user' => $user]);
        foreach ($cartItems as &$item) {
            $itemData = $normalizer->normalize($item, 'json', ['groups' => ['cartProducts', 'userProducts', 'category']]);
            $itemData['userId'] = $user->getId();
            $item = $itemData;
        }
        $json = $normalizer->normalize($cartItems, 'json');
        return new Response(json_encode($json));
    }


    #[Route('/api/addToCart/{productId}/{userId}', name: 'addToCartApi')]

    public function addToCartApi(ProductRepository $productRepository, UserRepository $userRepository, $productId, $userId): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $userRepository->find($userId);
        if (!$user) {
            return new Response('Utilisateur introuvable', Response::HTTP_BAD_REQUEST);
        }
        $product = $productRepository->find($productId);
        if (!$product) {
            return new Response("Ce produit n'existe pas", Response::HTTP_BAD_REQUEST);
        }
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

        return new Response('Produit ajouté avec success');
    }

    #[Route('/api/removeItem/{itemId}/{userId}', name: 'removeItemApi')]

    public function removeItemApi(ManagerRegistry $doctrine, ShoppingCartItemRepository $shoppingCartRepository, UserRepository $userRepository, $itemId, $userId): JsonResponse
    {
        $user = $userRepository->find($userId);
        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'Utilisateur introuvable'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $product = $shoppingCartRepository->find($itemId);
        if (!$product) {
            return new JsonResponse(['success' => false, 'message' => 'Produit introuvable'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $em = $doctrine->getManager();
        $em->remove($product);
        $em->flush();

        return new JsonResponse(['success' => true, 'message' => 'Product removed successfully']);
    }
}
