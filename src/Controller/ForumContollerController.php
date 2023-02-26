<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ForumFormType;

class ForumContollerController extends AbstractController
{
    #[Route('/forum/contoller', name: 'app_forum_contoller')]
    public function index(): Response
    {
        return $this->render('forum_contoller/index.html.twig', [
            'controller_name' => 'ForumContollerController',
        ]);
    }

    #[Route('/Choisir', name: 'app_Choisir')]
    public function choisir(ManagerRegistry $doctrine,Request $request)
    {   $form = $this->createForm(ForumFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if ($form->get("Post")->getData()==1) { 
                return $this->redirectToRoute("afficheP");
            }
            if ($form->get("Post")->getData()==2) { 
                return $this->redirectToRoute("afficheArticle");
            }
         }    
        return $this->renderForm("forum_contoller/choisir.html.twig",
        array("P"=>$form));
         }
    
}
