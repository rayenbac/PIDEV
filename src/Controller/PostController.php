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
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


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

    #[Route('/affichePC', name: 'affichePC')]
    public function affichePC(): Response
    {
    //récupérer le répository
    $r=$this->getDoctrine()->getRepository(Post::Class);
    //utiliser la fonction findAll()
    $c=$r->findAll();

        return $this->render('post/affichePC.html.twig', [
            'forum' => $c
        ]);
    } 


   
    #[Route('/afficheQ/{id}', name: 'afficheQ')]
    public function affichepQ($id,PostRepository $repository,ManagerRegistry $doctrine):  Response
                {
     //utiliser la fonction findAll()
        $s=$repository->findAll();
       
        $c= $doctrine->getRepository(Post::class)->find($id);
       
       
   return $this->render('post/afficheQuestion.html.twig', [
    
    'post'=>$c
                    ]);
     }
    

    #[Route('/APIafficheP', name: 'APIafficheP')]
    public function APIafficheP(PostRepository $repo , NormalizerInterface $normalizer)
    {
    $post = $repo->findAll();
    //Nous utilisions la fonction normalize quitransforme le tableau d'objets
    //post en tableau associatif simple 
    $postNormalises = $normalizer->normalize($post , 'json' , ['groups' => "post"]);
    // nous utilisons la fonction json_encode pour transfomer un tableau associatif en format json
    $json = json_encode($postNormalises);
    //nous renvoyons une reponse Http qui prend en parametre un tableau en format JSON
    return new Response($json);

    } 
    ///////////////////////////////////////////////////////////////////////////////
   
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
/////////////////////////////////////////////////////////////
#[Route('/APIaddP', name: 'APIaddP')]
    public function APIaddP(Request $req, NormalizerInterface $Normalizer){
        $em = $this->getDoctrine()->getManager();
        $post = new Post ();
        $post->setIDUser($req->get('ID_user'));
        $post->setNomUtilisateur($req->get('NomUtilisateur'));
        $post->setDescription($req->get('Description'));
        $post->setCreatedAt($req->get('createdAt'));
        $post->setUpdatedAt($req->get('updatedAt'));
        $post->setPublication($req->get('Publication'));
        
        $em->persist($post);
        $em->flush();

        $jsonContent = $Normalizer->normalize($post , 'json' , ['groups'=> 'post']);
        return new Response(json_encode($jsonContent));
    }
                  
////////////////////////////////////////////////////////////


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
////////////////////////////////////////////////////////////
#[Route('/APIupdatePost/{id}', name: 'APIupdatePost')]
    public function APIupdatePost(Request $req, $id, NormalizerInterface $Normalizer){
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Post::class)->find($id);
        $post->setIDUser($req->get('ID_user'));
        $post->setNomUtilisateur($req->get('NomUtilisateur'));
        $post->setDescription($req->get('Description'));
        $post->setCreatedAt($req->get('createdAt'));
        $post->setUpdatedAt($req->get('updatedAt'));
        $post->setPublication($req->get('Publication'));
        
       
        $em->flush();

        $jsonContent = $Normalizer->normalize($post , 'json' , ['groups'=> 'post']);
        return new Response("question updated successfully" . json_encode($jsonContent));
    }
////////////////////////////////////////////////////////////
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

 /////////////////////////////////////
#[Route('/APISuppPost/{id}', name: 'APISuppPost')]
public function APISuppPost(Request $req, $id, NormalizerInterface $Normalizer){
    $em = $this->getDoctrine()->getManager();
    $post = $em->getRepository(Post::class)->find($id);
    $em->remove($post);
    $em->flush();

    $jsonContent = $Normalizer->normalize($post , 'json' , ['groups'=> 'post']);
    return new Response("question deleted successfully" . json_encode($jsonContent));
}
/////////////////////////////////////



}
