<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReponseRepository;
use App\Entity\Reponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\ReponseType;





class ReponseController extends AbstractController
{
    #[Route('/reponse', name: 'app_reponse')]
    public function index(): Response
    {
        return $this->render('reponse/index.html.twig', [
            'controller_name' => 'ReponseController',
        ]);
    }

    #[Route('/afficheR', name: 'afficheR')]
    public function afficheR(ReponseRepository $repository): Response
                {
     //utiliser la fonction findAll()
        $s=$repository->findAll();
   return $this->render('reponse/afficheR.html.twig', [
    'reponse' => $s
                    ]);
     }

#[Route('/addR', name: 'addR')]
public function addR(ManagerRegistry $doctrine, Request $request)
{
$s = new Reponse();
$form = $this->createForm(ReponseType::class, $s);
$form->handleRequest($request);
if ($form->isSubmitted()) {
$em = $doctrine->getManager();
$em->persist($s);
$em->flush();
return $this->redirectToRoute("afficheF");
}
return $this->renderForm("reponse/addR.html.twig", array("f" => $form));
}

             #[Route('/updateReponse/{id}', name: 'updateReponse')]
               public function updateReponse(ReponseRepository $repository,
               $id,ManagerRegistry $doctrine,Request $request)
               { //récupérer le classroom à modifier
                   $reponse= $repository->find($id);
                   $form=$this->createForm(ReponseType::class,$reponse);
                   $form->handleRequest($request);
                   if($form->isSubmitted()){
                       $em =$doctrine->getManager();
                       $em->flush();
                       return $this->redirectToRoute("afficheF"); }
                   return $this->renderForm("reponse/addR.html.twig",
                       array("f"=>$form));
               } 
 #[Route('/suppReponse/{id}', name: 'suppReponse')]
           public function suppReponse($id,ReponseRepository $r,
           ManagerRegistry $doctrine): Response
           {//récupérer la classroom à supprimer
           $reponse=$r->find($id);
           //Action suppression
            $em =$doctrine->getManager();
            $em->remove($reponse);
            $em->flush();
 return $this->redirectToRoute('afficheF',);}  

}
