<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/addCommande', name: "addCommande")]
    public function addCommande(UserRepository $userRepository, ManagerRegistry $doctrine): Response
    {
        // get the user from the session 
        $user2 = $this->getUser();
        //check if the user is authenticated or not
        if (!$user2) {
            return $this->redirectToRoute('login');
        }
        $user = $userRepository->find($user2);
        //get the user from the repository to get the necessary informations such as his address
        $userAddress = $user->getAdresse();
        $userItems = $user->getShoppingCartItems();
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




        return $this->redirectToRoute('home');
    }
}
