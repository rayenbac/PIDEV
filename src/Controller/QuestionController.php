<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Question;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\QuestionRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\QuestionFormType;

class QuestionController extends AbstractController
{
    #[Route('/question', name: 'app_question')]
    public function index(): Response
    {
        return $this->render('question/index.html.twig', [
            'controller_name' => 'QuestionController',
        ]);
    }

    #[Route('/afficheF', name: 'afficheF')]
    public function afficheF(): Response
    {
    //récupérer le répository
    $r=$this->getDoctrine()->getRepository(Question::Class);
    //utiliser la fonction findAll()
    $c=$r->findAll();
        return $this->render('question/afficheF.html.twig', [
            'forum' => $c
        ]);
    }

    #[Route('/addQ', name: 'addQ')]
    public function addQ(ManagerRegistry $doctrine,Request $request)
                   {$question= new Question();
    $form=$this->createForm(QuestionFormType::class,$question);
                       $form->handleRequest($request);
                       if($form->isSubmitted() && $form->isValid()){
                           $em =$doctrine->getManager() ;
                           $em->persist($question);
                           $em->flush();
                           return $this->redirectToRoute("afficheF");}
                  return $this->renderForm("question/addQ.html.twig",
                           array("f"=>$form));
                    }


    #[Route('/updateQuestion/{id}', name: 'updateQuestion')]
               public function updateQuestion(QuestionRepository $repository,
               $id,ManagerRegistry $doctrine,Request $request)
               { //récupérer le classroom à modifier
                   $question= $repository->find($id);
                   $form=$this->createForm(QuestionFormType::class,$question);
                   $form->handleRequest($request);
                   if($form->isSubmitted() && $form->isValid()){
                       $em =$doctrine->getManager();
                       $em->flush();
                       return $this->redirectToRoute("afficheF"); }
                   return $this->renderForm("question/addQ.html.twig",
                       array("f"=>$form));
               } 
 #[Route('/suppQuestion/{id}', name: 'suppQuestion')]
           public function suppQuestion($id,QuestionRepository $r,
           ManagerRegistry $doctrine): Response
           {//récupérer la classroom à supprimer
           $question=$r->find($id);
           //Action suppression
            $em =$doctrine->getManager();
            $em->remove($question);
            $em->flush();
 return $this->redirectToRoute('afficheF',);}  

}
