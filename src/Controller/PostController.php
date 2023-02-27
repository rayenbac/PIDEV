<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\PostFormType;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Repository\CommentaireRepository;


class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }
   

    #[Route('/afficheP', name: 'afficheP')]
    public function afficheP(): Response
    {
    //récupérer le répository
    $r=$this->getDoctrine()->getRepository(Post::Class);
    //utiliser la fonction findAll()
    $c=$r->findAll();
        return $this->render('post/afficheP.html.twig', [
            'forum' => $c
        ]);
    } 
   
    #[Route('/afficheA', name: 'afficheA')]
    public function afficheA(): Response
    {
    //récupérer le répository
    $r=$this->getDoctrine()->getRepository(Post::Class);
    //utiliser la fonction findAll()
    $c=$r->findAll();
        return $this->render('Post/afficheA.html.twig', [
            'forum' => $c
        ]);
    }

    #[Route('/addP', name: 'addP')]
    public function addP(ManagerRegistry $doctrine,Request $request)
                   {$post= new Post();
                    
                    $form=$this->createForm(PostFormType::class,$post);
  
                       $form->handleRequest($request);
                       if($form->isSubmitted() && $form->isValid()){
                        $currenttime = new \DateTime();
                        $post->setCreatedAt($currenttime);
                       $post->setUpdatedAt($currenttime);
                       
                       
                           $em =$doctrine->getManager() ; // EM gestionnaire de l'entite (crud bd)
                           $em->persist($post); //ajout
                           $em->flush(); // persist->bd
                           return $this->redirectToRoute("afficheP");}
                  return $this->renderForm("post/addP.html.twig",
                           array("f"=>$form));
                    }

                    #[Route('/updatePost/{id}', name: 'updatePost')]
               public function updatePost(PostRepository $repository,
               $id,ManagerRegistry $doctrine,Request $request)
               { //récupérer le classroom à modifier
                   $post1= $repository->find($id);
                   $post=new Post();
                    $currenttime = new \DateTime();
                    
                   $form=$this->createForm(PostFormType::class,$post);
                   $form->get("NomUtilisateur")->setData($post1->getNomUtilisateur());
                   $form->get("ID_user")->setData($post1->getIDUser());
                   $form->get("Description")->setData($post1->getDescription());
                   $form->get("Publication")->setData($post1->getPublication());


                   $form->handleRequest($request);
                   if($form->isSubmitted() && $form->isValid()){
                    $post1->setNomUtilisateur($form->get("NomUtilisateur")->getData());
                    $post1->setIDUser($form->get("ID_user")->getData());
                    $post1->setDescription($form->get("Description")->getData());
                    $post1->setPublication($form->get("Publication")->getData());
                    $post1->setCreatedAt($post1->getCreatedAt());
                    $post1->setUpdatedAt($currenttime );
                    
                       $em =$doctrine->getManager();
                       $em->persist($post1);
                       $em->flush();
                       return $this->redirectToRoute("afficheP"); }
                   return $this->renderForm("Post/addP.html.twig",
                       array("f"=>$form));
               } 
 #[Route('/suppPost/{id}', name: 'suppPost')]
           public function suppPost($id,PostRepository $r,
           ManagerRegistry $doctrine): Response
           {//récupérer la classroom à supprimer
           $post=$r->find($id);
           //Action suppression
            $em =$doctrine->getManager();
            $em->remove($post);
            $em->flush();
 return $this->redirectToRoute('afficheP',);}  


}
