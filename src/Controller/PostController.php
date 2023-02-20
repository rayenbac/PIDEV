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
                       
                       
                           $em =$doctrine->getManager() ;
                           $em->persist($post);
                           $em->flush();
                           return $this->redirectToRoute("afficheP");}
                  return $this->renderForm("post/addP.html.twig",
                           array("f"=>$form));
                    }

                    #[Route('/updatePost/{id}', name: 'updatePost')]
               public function updatePost(PostRepository $repository,
               $id,ManagerRegistry $doctrine,Request $request)
               { //récupérer le classroom à modifier
                   $post= $repository->find($id);
                   $form=$this->createForm(PostFormType::class,$post);
                   $form->handleRequest($request);
                   if($form->isSubmitted() && $form->isValid()){
                    $currenttime = new \DateTime();
                    $post->setUpdatedAt($currenttime);
                       $em =$doctrine->getManager();
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
