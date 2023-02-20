<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Commentaire;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\CommentaireFormType;
use Symfony\Component\HttpFoundation\Response;


class CommentaireController extends AbstractController
{
    #[Route('/commentaire', name: 'app_commentaire')]
    public function index(): Commentaire
    {
        return $this->render('commentaire/index.html.twig', [
            'controller_name' => 'CommentaireController',
        ]);
    }

    #[Route('/afficheC', name: 'afficheC')]
    public function afficheC(CommentaireRepository $repository):  Response
                {
     //utiliser la fonction findAll()
        $s=$repository->findAll();
   return $this->render('commentaire/afficheC.html.twig', [
    'commentaire' => $s
                    ]);
     }

     #[Route('/addC', name: 'addC')]
    public function addC(ManagerRegistry $doctrine,Request $request)
                   {$commentaire= new Commentaire();
    $form=$this->createForm(CommentaireFormType::class,$commentaire);
                       $form->handleRequest($request);
                       if($form->isSubmitted() && $form->isValid()){
                           $em =$doctrine->getManager() ;
                           $em->persist($commentaire);
                           $em->flush();
                           return $this->redirectToRoute("afficheA");}
                  return $this->renderForm("commentaire/addC.html.twig",
                           array("f"=>$form));
                    }

           #[Route('/updateCommentaire/{id}', name: 'updateCommentaire')]
               public function updateCommentaire(CommentaireRepository $repository,
               $id,ManagerRegistry $doctrine,Request $request)
               { //récupérer le classroom à modifier
                   $commentaire= $repository->find($id);
                   $form=$this->createForm(CommentaireFormType::class,$commentaire);
                   $form->handleRequest($request);
                   if($form->isSubmitted()){
                       $em =$doctrine->getManager();
                       $em->flush();
                       return $this->redirectToRoute("afficheA"); }
                   return $this->renderForm("commentaire/addC.html.twig",
                       array("p"=>$form));
               } 
               #[Route('/suppCommentaire/{id}', name: 'suppCommentaire')]
               public function suppCommentaire($id,CommentaireRepository $r,
               ManagerRegistry $doctrine): Response
               {//récupérer la classroom à supprimer
               $reponse=$r->find($id);
               //Action suppression
                $em =$doctrine->getManager();
                $em->remove($reponse);
                $em->flush();
     return $this->redirectToRoute('afficheA',);}  
     
     
}

