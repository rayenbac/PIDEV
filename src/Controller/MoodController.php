<?php

namespace App\Controller;


use App\Entity\Mood;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\MoodRepository;
use App\Form\FormMoodType;

class MoodController extends AbstractController
{
    #[Route('/mood', name: 'app_mood')]
    public function index(): Response
    {
        return $this->render('mood/index.html.twig', [
            'controller_name' => 'MoodController',
        ]);
    }

    #[Route('/afficheM', name: 'afficheM')]
    public function afficheM(): Response
                {
     //récupérer le repository
     $c=$this->getDoctrine()->getRepository(Mood::Class)->findAll();
     //utiliser la fonction findAll()
     //$c=$r->findAll();
   return $this->render('mood/index.html.twig', [
    'm' => $c
                    ]);
     }


     



     #[Route('/add/mood', name: 'addMood')]
     public function addMood(ManagerRegistry $doctrine,Request $request)
                    {$mood= new Mood();
                     
                     $form=$this->createForm(FormMoodType::class,$mood);
   
                        $form->handleRequest($request);
                        if($form->isSubmitted() && $form->isValid()){
                         
                        
                        
                            $em =$doctrine->getManager() ;
                            $em->persist($mood);
                            $em->flush();
                            return $this->redirectToRoute("afficheM");}
                   return $this->renderForm("mood/add.html.twig",
                            array("f"=>$form));
                     }

    
    #[Route('/editmood/{id}', name: 'editmood')]
    public function editmood (MoodRepository $repository,
    $id,ManagerRegistry $doctrine,Request $request)
    { //récupérer le classroom à modifier
        $mood= $repository->find($id);
        $form=$this->createForm(FormMoodType::class,$mood);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em =$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute("afficheM"); }
        return $this->renderForm("mood/edit.html.twig",
            array("f"=>$form));
    }
    

    #[Route('/deletemood/{id}', name:'deletemood')]
    public function deletemood($id, ManagerRegistry $doctrine , MoodRepository $r): Response
    {
        $mood=$r->find($id);
        $em=$doctrine->getManager();
        $em->remove($mood);
        $em->flush();
        return $this->redirectToRoute('afficheM'); 


    }
    #[Route('/afficheAdminMood', name: 'afficheAdminMood')]
     public function afficheAdminMood(): Response
                 {
      //récupérer le repository
      $c=$this->getDoctrine()->getRepository(Mood::Class)->findAll();
      //utiliser la fonction findAll()
      //$c=$r->findAll();
    return $this->render("mood/afficheA.html.twig", [
     'm' => $c
                     ]);
      }
}



