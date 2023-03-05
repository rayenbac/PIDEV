<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\JournalMoodRepository;
use App\Form\JournalMoodFormType;
use App\Entity\JournalMood;
use App\Entity\Mood;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\MoodRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


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
        // récupérer le repository
        $repository = $this->getDoctrine()->getRepository(JournalMood::class);
        
        // récupérer tous les journaux d'humeur
        $journals = $repository->findAll();
        
        // retourner la vue avec les journaux d'humeur récupérés
        return $this->render('journal_mood/indexJ.html.twig', [
            'journals' => $journals
        ]);
    }



     #[Route('/add/journal', name: 'addjournal')]
     public function addjournal(ManagerRegistry $doctrine,Request $request ,JournalMoodRepository $repository,MoodRepository $r)
                    {$journal= new JournalMood();
                     
                     $form=$this->createForm(JournalMoodFormType::class,$journal);
                     $c=$this->getDoctrine()->getRepository(Mood::Class)->findAll();
                     
                        $form->handleRequest($request);
                        if($form->isSubmitted() && $form->isValid()){
                            
                       
                         
                        
                        
                            $em =$doctrine->getManager() ;
                            $em->persist($journal);
                            $em->flush();
                            return $this->redirectToRoute("afficheJ");}
                   return $this->renderForm("journal_mood/addJ.html.twig",['f' => $form,
                            'moods'=>$c],
                            );
                     }

    
    #[Route('/editjournal/{id}', name: 'editjournal')]
    public function editjournal (JournalMoodRepository $repository,
    $id,ManagerRegistry $doctrine,Request $request,MoodRepository $r)
    { //récupérer le classroom à modifier
        $journal= $repository->find($id);
        $form=$this->createForm(JournalMoodFormType::class,$journal);
        $c=$this->getDoctrine()->getRepository(Mood::Class)->findAll();
                     
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em =$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute("afficheJ"); }
        return $this->renderForm("journal_mood/editJ.html.twig",['f' => $form,
        'moods'=>$c],
        );
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


    

    #[Route('/affichePI', name: 'affichePI')]
    public function affichePI(JournalMoodRepository $repo, NormalizerInterface $normalizer)
    {
        $journals = $this->getDoctrine()->getRepository(JournalMood::class)->findAll();
         $journalsNormalises = $normalizer->normalize($journals, 'json', ['groups' => "journals"]);
    
         $json = json_encode($journalsNormalises);
    
         return new Response($json);
    }
     
    
    
    #[Route('/addJournalJSON/new', name: 'addJournalJSON')]
    public function addJournalJSON(Request $req, NormalizerInterface $Normalizer)
    {
         $em = $this->getDoctrine()->getManager();
         $journal = new JournalMood();
         $journal->setIdUser($req->get('IdUser'));
         $journal->setMoods($req->get('moods'));
         $em->persist($journal);
         $em->flush();
    
         $jsonContent = $Normalizer->normalize($journal, 'json', ['groups' => 'journals']);
         return new Response(json_encode($jsonContent));
    }
    
    
    #[Route('/updateJournalJSON/{id}', name: 'updateJournalJSON')]
    public function updateJournalJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {
         $em = $this->getManager();
         $journal = $em->getRepository(JournalMood::class)->find($id);
         $journal->setIdUser($req->get('IdUser'));
         $journal->setMoods($req->get('moods'));
    
         $em->flush();
    
         $jsonContent = $Normalizer->normalize($journal, 'json', ['groups' => 'journals']);
         return new Response("Journal Mood updated successfully" . json_encode($jsonContent));
    }
    
    
    #[Route('/deleteJournalJSON/{id}', name: 'deleteJournalJSON')]
    public function deleteJournalJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {
         $em = $this->getManager();
         $journal = $em->getRepository(JournalMood::class)->find($id);
         $em->remove($journal);
         $em->flush();
    
         $jsonContent = $Normalizer->normalize($journal, 'json', ['groups' => 'journals']);
         return new Response("Journal Mood deleted successfully" . json_encode($jsonContent));
    }
    
}
