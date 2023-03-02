<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Commentaire;
use App\Entity\Post;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\CommentaireFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class CommentaireController extends AbstractController
{
    #[Route('/commentaire', name: 'app_commentaire')]
    public function index(): Commentaire
    {
        return $this->render('commentaire/index.html.twig', [
            'controller_name' => 'CommentaireController',
        ]);
    }

    #[Route('/afficheC/{id}', name: 'afficheC')]
    public function afficheC($id,CommentaireRepository $repository,ManagerRegistry $doctrine):  Response
                {
     //utiliser la fonction findAll()
        $s=$repository->findAll();
       
        $c= $doctrine->getRepository(Commentaire::class)->findByPostId($id);
       
       
   return $this->render('commentaire/afficheC.html.twig', [
    'commentaire' => $s,
    'post'=>$c
                    ]);
     }
     #[Route('/afficheReponse/{id}', name: 'afficheReponse')]
     public function afficheReponse($id,CommentaireRepository $repository,ManagerRegistry $doctrine):  Response
                 {
      //utiliser la fonction findAll()
         $s=$repository->findAll();
        
         $c= $doctrine->getRepository(Commentaire::class)->findByPostId($id);
        
        
    return $this->render('commentaire/afficheReponse.html.twig', [
     'commentaire' => $s,
     'post'=>$c
                     ]);
      }
      ////////////////////////////////////////////////////////////////////////////////
      #[Route('/APIafficheReponse/{id}', name: 'APIafficheReponse')]
      public function APIafficheReponse( $id ,CommentaireRepository $repo , NormalizerInterface $normalizer)
      {
      $commentaire = $repo->findByPostId($id);

      $commentaireNormalises = $normalizer->normalize($commentaire , 'json' , ['groups' => "commentaire"]);
      //nous renvoyons une reponse Http qui prend en parametre un tableau en format JSON
      return new Response(json_encode($commentaireNormalises));
  
      } 

      ///////////////////////////////////////////////////////////////////////////////

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

