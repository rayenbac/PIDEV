<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Form\ArticleFormType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;



class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
   
    #[Route('/afficheArticle', name: 'afficheArticle')]
    public function afficheArticle(): Response
    {
    //récupérer le répository
    $r=$this->getDoctrine()->getRepository(Article::Class);
    //utiliser la fonction findAll()
    $c=$r->findAll();
        return $this->render('article/afficheArticle.html.twig', [
            'forum' => $c
        ]);
    }
    #[Route('/APIafficheArticle', name: 'APIafficheArticle')]
    public function APIafficheP(ArticleRepository $repo , NormalizerInterface $normalizer)
    {
    $article = $repo->findAll();
    //Nous utilisions la fonction normalize quitransforme le tableau d'objets
    //post en tableau associatif simple 
    $articleNormalises = $normalizer->normalize($article , 'json' , ['groups' => "article"]);
    // nous utilisons la fonction json_encode pour transfomer un tableau associatif en format json
    $json = json_encode($articleNormalises);
    //nous renvoyons une reponse Http qui prend en parametre un tableau en format JSON
    return new Response($json);

    } 
  
    #[Route('/afficheAA', name: 'afficheAA')]
    public function afficheAA(): Response
    {
    //récupérer le répository
    $r=$this->getDoctrine()->getRepository(Article::Class);
    //utiliser la fonction findAll()
    $c=$r->findAll();
        return $this->render('article/afficheAA.html.twig', [
            'forum' => $c
        ]);
    }
    #[Route('/addArticle', name: 'addArticle')]
    public function addArticle(ManagerRegistry $doctrine,Request $request  , SluggerInterface $slugger)
                   {$article= new Article();
    $form=$this->createForm(ArticleFormType::class,$article);
                       $form->handleRequest($request);
                       if($form->isSubmitted() && $form->isValid()){
                        $currenttime = new \DateTime();
                        $article->setCreatedAt($currenttime);
                        $article->setUpdatedAt($currenttime);
                        $brochureFile = $form->get('photo')->getData();

                        // this condition is needed because the 'brochure' field is not required
                        // so the PDF file must be processed only when a file is uploaded
                        if ($brochureFile) {
                            $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                            // this is needed to safely include the file name as part of the URL
                            $safeFilename = $slugger->slug($originalFilename);
                            $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
            
                            // Move the file to the directory where brochures are stored
                            try {
                                $brochureFile->move(
                                    $this->getParameter('articles_directory'),
                                    $newFilename
                                );
                            } catch (FileException $e) {
                                // ... handle exception if something happens during file upload
                            }
            
                            // updates the 'brochureFilename' property to store the PDF file name
                            // instead of its contents
                            $article->setImage($newFilename);
                        }
            
                           $em =$doctrine->getManager() ;
                           $em->persist($article);
                           $em->flush();
                           return $this->redirectToRoute("afficheArticle");}
                  return $this->renderForm("article/addArticle.html.twig",
                           array("f"=>$form));
                    }


                   
                  
                    #[Route('/updateArticle/{id}', name: 'updateArticle')]
               public function updateArticle(ArticleRepository $repository,
               $id,ManagerRegistry $doctrine,Request $request)
               { 
                   $article1= $repository->find($id);
                   $article=new Article();
                    $currenttime = new \DateTime();
                    
                   $form=$this->createForm(ArticleFormType::class,$article);
                   $form->get("Id_user")->setData($article1->getIdUser());
                   $form->get("NomUtilisateur")->setData($article1->getNomUtilisateur());
                   $form->get("article")->setData($article1->getArticle());
                   $form->get("photo")->setData($article1->getImage());


                   $form->handleRequest($request);
                   if($form->isSubmitted() && $form->isValid()){
                    $article1->setIdUser($form->get("Id_user")->getData());
                    $article1->setNomUtilisateur($form->get("NomUtilisateur")->getData());
                    $article1->setArticle($form->get("article")->getData());
                    $article1->setImage($form->get("photo")->getData());
                    $article1->setCreatedAt($article1->getCreatedAt());
                    $article1->setUpdatedAt($currenttime );
                    
                       $em =$doctrine->getManager();
                       $em->persist($post1);
                       $em->flush();
                       return $this->redirectToRoute("afficheAA"); }
                   return $this->renderForm("article/addArticle.html.twig",
                       array("f"=>$form));
               } 
             

      #[Route('/suppArticle/{id}', name: 'suppArticle')]
                public function suppArticle($id,ArticleRepository $r,
                ManagerRegistry $doctrine): Response
                {//récupérer la classroom à supprimer
                $article=$r->find($id);
                //Action suppression
                 $em =$doctrine->getManager();
                 $em->remove($article);
                 $em->flush();
      return $this->redirectToRoute('afficheAA',);}  
     

    
}
