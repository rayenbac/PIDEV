<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Post;
use App\Repository\PostRepository;

class LikesController extends AbstractController
{
    #[Route('/likes/{id}', name: 'app_likes')]
    public function index($id , ManagerRegistry $doctrine): Response
    {   
        $r=$this->getDoctrine()->getRepository(Post::Class);
        //utiliser la fonction findby()
        $c=$r->find($id);

        $likes = $c->getLikes();

        //Incrémentation
       // if ($likes=0) {
            $plusDeLikes = $likes + 1 ;
       // } else {  $plusDeLikes = 1 ;}
      

        //Je mets à jour mon champ la table Post
        $c->setLikes($plusDeLikes);
        $em =$doctrine->getManager() ;
        $em->flush();

        return $this->redirectToRoute('afficheP')

         ;
    }
    #[Route('/dislikes/{id}', name: 'app_dislikes')]
    public function app_Dislikes($id , ManagerRegistry $doctrine): Response
    {   
        $r=$this->getDoctrine()->getRepository(Post::Class);
        //utiliser la fonction findby()
        $c=$r->find($id);

        $dislikes = $c->getDislike();

        //Incrémentation
        
        $plusDedislikes = $dislikes + 1 ;
        
      

        //Je mets à jour mon champ la table Post
        $c->setDislike($plusDedislikes);
        $em =$doctrine->getManager() ;
        $em->flush();

        return $this->redirectToRoute('afficheP')

         ;
    }
    #[Route('/rate/{id}', name: 'app_rate')]
    public function rate($id , ManagerRegistry $doctrine): Response
    {   
        $r=$this->getDoctrine()->getRepository(Post::Class);
        //utiliser la fonction findby()
        $c=$r->find($id);

        $rate = $c->getRate();

        //Incrémentation
        if ($rate<5) {
         
            $plusDeRate = $rate + 1 ;
        } else {  $plusDeRate = 5 ;}
      

        //Je mets à jour mon champ la table Post
        $c->setRate($plusDeRate);
        $em =$doctrine->getManager() ;
        $em->flush();

        return $this->redirectToRoute('afficheP')

         ;
    }
}
