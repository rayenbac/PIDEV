<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\JournalMoodRepository;
use App\Form\JournalMoodFormType;
use App\Entity\JournalMood;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\MoodRepository;


class JournalMoodController extends AbstractController
{
    #[Route('/journal/mood', name: 'app_journal_mood')]
    public function index(): Response
    {
        return $this->render('journal_mood/index.html.twig', [
            'controller_name' => 'JournalMoodController',
        ]);
    }
    #[Route('/afficheJ', name: 'afficheJ')]
    public function afficheJ(): Response
                {
     //récupérer le repository
     $c=$this->getDoctrine()->getRepository(JournalMood::Class)->findAll();
     //utiliser la fonction findAll()
     //$c=$r->findAll();
   return $this->render('journal_mood/indexJ.html.twig', [
    'j' => $c
                    ]);
     }
     #[Route('/add/journal', name: 'addjournal')]
     public function addjournal(ManagerRegistry $doctrine,Request $request ,JournalMoodRepository $repository)
                    {$journal= new JournalMood();
                     
                     $form=$this->createForm(JournalMoodFormType::class,$journal);
   
                        $form->handleRequest($request);
                        if($form->isSubmitted() && $form->isValid()){
                           
                         
                        
                        
                            $em =$doctrine->getManager() ;
                            $em->persist($journal);
                            $em->flush();
                            return $this->redirectToRoute("afficheJ");}
                   return $this->renderForm("journal_mood/addJ.html.twig",
                            array("f"=>$form));
                     }

    
    #[Route('/editjournal/{id}', name: 'editjournal')]
    public function editjournal (JournalMoodRepository $repository,
    $id,ManagerRegistry $doctrine,Request $request)
    { //récupérer le classroom à modifier
        $journal= $repository->find($id);
        $form=$this->createForm(JournalMoodFormType::class,$journal);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em =$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute("afficheJ"); }
        return $this->renderForm("journal_mood/editJ.html.twig",
            array("f"=>$form));
    }
    

    #[Route('/deletejournal/{id}', name:'deletejournal')]
    public function journal($id, ManagerRegistry $doctrine , JournalMoodRepository $r): Response
    {
        $journal=$r->find($id);
        $em=$doctrine->getManager();
        $em->remove($journal);
        $em->flush();
        return $this->redirectToRoute('afficheJ'); 


    }
}
