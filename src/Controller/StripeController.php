<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\ShoppingCartItemRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Stripe\BalanceTransaction;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class StripeController extends AbstractController
{
    #[Route('/commande/createSession', name: 'app_stripe')]
    public function index(ShoppingCartItemRepository $shoppingCartRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return new Response('Utilisateur introuvable', Response::HTTP_BAD_REQUEST);
        }
        \Stripe\Stripe::setApiKey('sk_test_51MhGaAGeGEgrQ6hOFaUPvKPr8iOv7UjDwPJ22UAHMhCVD0VCQw3CmEGh0mQoVN7b635WeO2rilB94j2hSWMNDxhu00UQXAHDAc');
        $userItems = $shoppingCartRepository->findBy(['user' => $user]);
        $stripeProducts = [];
        foreach ($userItems as $item) {
            $product = $item->getProduct();
            $product = \Stripe\Product::create([
                'name' => $product->getName(),
                'images' => ["http://localhost:8000/uploads/products/" . $product->getImage()],
            ]);

            // Create a price for the product
            $price = \Stripe\Price::create([
                'unit_amount' => $item->getProduct()->getPrice() * 100, // Convert price to cents
                'currency' => 'eur',
                'product' => $product->id,
            ]);

            // Add the price to the Stripe products array
            $stripeProducts[] = [
                'price' => $price->id,
                'quantity' => $item->getQuantity(),
            ];
        }

        header('Content-Type: application/json');
        $YOUR_DOMAIN = 'http://localhost:8000';
        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => [$stripeProducts],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success_route', [], UrlGeneratorInterface::ABSOLUTE_URL),


            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);
        return new JsonResponse(['id' => $checkout_session->id]);
    }
    #[Route('/success', name: 'success_route')]
    public function success(UserRepository $userRepository, ManagerRegistry $doctrine, MailerInterface $mailer): Response
    {
        $user2 = $this->getUser();
        //check if the user is authenticated or not
        if (!$user2) {
            return new Response('Utilisateur introuvable', Response::HTTP_BAD_REQUEST);
        }
        $user = $userRepository->find($user2);
        //get the user from the repository to get the necessary informations such as his address
        $userAddress = $user->getAdresse();
        $userItems = $user->getShoppingCartItems();
        $userEmail = $user->getEmail();
        $commande = new Commande();
        $currenttime = new DateTime();
        $commande->setCreatedAt($currenttime);
        $commande->setAddresse($userAddress);
        // add all the shopping cart items to the commande
        foreach ($userItems as $item) {
            $commande->addItem($item);
        }
        $commande->setUser($user);
        $commande->setIsConfirmed(0);
        //check if all the product's quantities are enough
        foreach ($userItems as $item) {
            $product = $item->getProduct();
            //get the available quantity of the product
            $productAvailableQuantity = $product->getQuantity();
            //get the quantity that will be substracted when confirming the order
            $quantityTosubstract = $item->getQuantity();
            $isAvailable = ($productAvailableQuantity - $quantityTosubstract) >= 0;
            if (!$isAvailable) {
                return new Response("Produit non disponible", Response::HTTP_BAD_REQUEST);
            }
        }
        $stripeProducts = [];
        foreach ($userItems as $item) {
            $product = $item->getProduct();
            //get the available quantity of the product
            $productInitialQuantity = $product->getQuantity();
            //get the quantity that will be substracted when confirming the order
            $quantityTosubstract = $item->getQuantity();
            $product->setQuantity($productInitialQuantity - $quantityTosubstract);
        }
        foreach ($user->getShoppingCartItems() as $item) {
            $user->removeShoppingCartItem($item);
        }
        $em = $doctrine->getManager();
        $em->persist($commande);
        $em->flush();
        //mail 

        $email = (new Email())

            ->from('echkiliboucha@gmail.com')
            ->to($userEmail)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Commande confrimée !')
            ->text('Votre Commande est confirmée ')
            ->html('<p>Merci de nous faire confiance,vous serez livré(e) dans les plus brefs délais  </p>');



        $mailer->send($email);



        return $this->redirectToRoute('home');
    }
    #[Route('/dashboard/stats', name: 'stats')]
    public function stats(): Response
    {
        Stripe::setApiKey('sk_test_51MhGaAGeGEgrQ6hOFaUPvKPr8iOv7UjDwPJ22UAHMhCVD0VCQw3CmEGh0mQoVN7b635WeO2rilB94j2hSWMNDxhu00UQXAHDAc');

        // Calculate the start and end timestamps for the last 7 days
        $startDate = strtotime('-7 days');
        $endDate = time();

        // Retrieve the balance transactions for the last 7 days
        $transactions = BalanceTransaction::all([
            'created' => [
                'gte' => $startDate,
                'lte' => $endDate,
            ],
        ]);

        // Initialize an empty array to hold the daily revenue data
        $dailyRevenueData = [];

        // Loop through the transactions to calculate the daily revenue data
        foreach ($transactions->autoPagingIterator() as $transaction) {
            if ($transaction->type === 'charge') {
                $transactionDate = date('Y-m-d', $transaction->created);
                if (!isset($dailyRevenueData[$transactionDate])) {
                    $dailyRevenueData[$transactionDate] = 0;
                }
                $dailyRevenueData[$transactionDate] += $transaction->amount / 100;
            }
        }

        // Sort the daily revenue data by date
        ksort($dailyRevenueData);

        // Prepare the data for the Chart.js bar chart
        $chartData = [
            'labels' => [],
            'data' => [],
        ];
        foreach ($dailyRevenueData as $date => $revenue) {
            $chartData['labels'][] = $date;
            $chartData['data'][] = $revenue;
        }

        // Render the twig template and pass the data to the view
        return $this->render('stripe/stats.html.twig', [
            'chartData' => $chartData,
        ]);
    }
}
