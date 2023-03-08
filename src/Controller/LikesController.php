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
    #[Route('/likesM/{id}', name: 'app_likess')]
    public function index($id, ManagerRegistry $doctrine): Response
    {
        $r = $this->getDoctrine()->getRepository(Post::Class);
        //utiliser la fonction findby()
        $c = $r->find($id);

        $p = $this->getDoctrine()->getRepository(Post::Class);
        //utiliser la fonction findby()
        $m = $p->find($id);

        $dislikes = $m->getDislike();
        $likes = $c->getLikes();

        //Incrémentation
        // if ($likes=0) {
        $plusDeLikes = $likes + 1;
        $dislikes = $dislikes - 1;
        // } else {  $plusDeLikes = 1 ;}


        //Je mets à jour mon champ la table Post
        $c->setLikes($plusDeLikes);
        $m->setDislike($dislikes);
        $em = $doctrine->getManager();
        $em->flush();

        return $this->redirectToRoute('afficheP');
    }
    #[Route('/dislikesM/{id}', name: 'app_dislikess')]
    public function app_Dislikes($id, ManagerRegistry $doctrine): Response
    {
        $a = $this->getDoctrine()->getRepository(Post::Class);
        //utiliser la fonction findby()
        $m = $a->find($id);
        $r = $this->getDoctrine()->getRepository(Post::Class);
        //utiliser la fonction findby()
        $c = $r->find($id);

        $dislikes = $c->getDislike();
        $likes = $m->getLikes();

        //Incrémentation

        $plusDedislikes = $dislikes + 1;
        $likes = $likes - 1;



        //Je mets à jour mon champ la table Post
        $c->setDislike($plusDedislikes);
        $m->setLikes($likes);
        $em = $doctrine->getManager();
        $em->flush();

        return $this->redirectToRoute('afficheP');
    }
    #[Route('/rate/{id}', name: 'app_rate')]
    public function rate($id, ManagerRegistry $doctrine): Response
    {
        $r = $this->getDoctrine()->getRepository(Post::Class);
        //utiliser la fonction findby()
        $c = $r->find($id);

        $rate = $c->getRate();

        //Incrémentation
        if ($rate < 5) {

            $plusDeRate = $rate + 1;
        } else {
            $plusDeRate = 5;
        }


        //Je mets à jour mon champ la table Post
        $c->setRate($plusDeRate);
        $em = $doctrine->getManager();
        $em->flush();

        return $this->redirectToRoute('afficheP');
    }
}
