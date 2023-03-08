<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Evenements;
use App\Repository\EvenementsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LikeDislikeController extends AbstractController
{
    #[Route('/likes/{id}', name: 'app_likesM')]
    public function index($id, ManagerRegistry $doctrine): Response
    {
        $r = $this->getDoctrine()->getRepository(Evenements::Class);
        //utiliser la fonction findby()
        $c = $r->find($id);
        $a = $this->getDoctrine()->getRepository(Evenements::Class);
        //utiliser la fonction findby()
        $p = $r->find($id);
        $likes = $c->getLikes();
        $dislikes = $p->getDislikes();

        //Incrémentation
        // if ($likes=0) {
        $plusDeLikes = $likes + 1;
        $dislikes = $dislikes - 1;
        // } else {  $plusDeLikes = 1 ;}


        //Je mets à jour mon champ la table 
        $c->setLikes($plusDeLikes);
        $p->setDislikes($dislikes);
        $em = $doctrine->getManager();
        $em->flush();

        return $this->redirectToRoute('afficheE');
    }
    #[Route('/dislikes/{id}', name: 'app_dislikesM')]
    public function app_Dislikes($id, ManagerRegistry $doctrine): Response
    {
        $r = $this->getDoctrine()->getRepository(Evenements::Class);
        //utiliser la fonction findby()
        $c = $r->find($id);
        $a = $this->getDoctrine()->getRepository(Evenements::Class);
        //utiliser la fonction findby()
        $p = $r->find($id);

        $dislikes = $c->getDislikes();
        $likes = $c->getLikes();

        //Incrémentation

        $plusDedislikes = $dislikes + 1;
        $likes = $likes - 1;



        //Je mets à jour mon champ 
        $c->setDislikes($plusDedislikes);
        $p->setLikes($likes);
        $em = $doctrine->getManager();
        $em->flush();

        return $this->redirectToRoute('afficheE');
    }

    public function decrementLikes(Request $request)
    {
        // Récupérer l'ID de l'élément envoyé depuis la requête AJAX
        $id = $request->request->get('id');

        // Charger l'entité correspondante depuis la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entity = $entityManager->getRepository(Evenements::class)->find($id);

        // Décrémenter le nombre de likes de l'entité
        $entity->setLikes($entity->getLikes() - 1);

        // Enregistrer les changements dans la base de données
        $entityManager->persist($entity);
        $entityManager->flush();

        // Retourner une réponse JSON pour indiquer que l'opération a réussi
        return new JsonResponse(['success' => true]);
    }
}
