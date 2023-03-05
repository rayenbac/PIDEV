<?php

namespace App\Controller;

use App\Repository\ShoppingCartItemRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'app_stripe')]
    public function index(ShoppingCartItemRepository $shoppingCartRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return new Response('Utilisateur introuvable', Response::HTTP_BAD_REQUEST);
        }
        $userItems = $shoppingCartRepository->findBy(['user' => $user]);
        foreach ($userItems as $item) {
            $product = $item->getProduct();
            $stripeProducts[] = [
                'price_data' =>
                [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' =>
                    [
                        'name' => $product->getName(),
                        'images' => "http://localhost:8000/uploads/products/" . $product->getImage()
                    ]
                ],
                'quantity' => $item->getQuantity()
            ];
        }
        \Stripe\Stripe::setApiKey('sk_test_51MhGaAGeGEgrQ6hOFaUPvKPr8iOv7UjDwPJ22UAHMhCVD0VCQw3CmEGh0mQoVN7b635WeO2rilB94j2hSWMNDxhu00UQXAHDAc');
        header('Content-Type: application/json');
        $YOUR_DOMAIN = 'http://localhost:8000';
        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => [$stripeProducts],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);
        return new JsonResponse(['id' => $checkout_session->id]);
    }
}
