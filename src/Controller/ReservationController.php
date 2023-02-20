<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reservation;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ReservationRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\ReservationFormType;
use App\Repository\EvenementsRepository;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
 
   }
   #[Route('/afficheRAdmin', name: 'afficheRAdmin')]
    public function afficheRAdmin(ReservationRepository $repository ): Response
                {
                // $e=$this->getDoctrine()->getRepository(Reservation::Class);
     
        $info=$repository->findAll();
      
   return $this->render('reservation/afficheRAdmin.html.twig', [
    'reservation' => $info,
                    ]);
     }
     #[Route('/afficheReservation/{email}', name: 'afficheReservation')]
    public function afficheReservation (ReservationRepository $repository , $email): Response
                {
                // $e=$this->getDoctrine()->getRepository(Reservation::Class);
     
        $info=$repository->findBy(array('Email'=>$email));
      
   return $this->render('reservation/afficheReservation.html.twig', [
    'reservation' => $info,
                    ]);
     }
     #[Route('/addR', name: 'addR')]
     public function addR(ManagerRegistry $doctrine,Request $request,EvenementsRepository $repository)
                    {   $complet='';
                        $reservation= new Reservation();
     $form=$this->createForm(ReservationFormType::class,$reservation);
     
                        $form->handleRequest($request);
                        if($form->isSubmitted() && $form->isValid()){
                            $nomevenement= $repository->find($reservation->getEvenements()->getId());
                            if ($nomevenement->getNbrDePlaces()){
                            $nomevenement->setNbrDePlaces($nomevenement->getNbrDePlaces()-$reservation->getNombreDePlaceAReserver());
                            $em =$doctrine->getManager() ;
                            $em->persist($reservation);
                            $em->flush();
                            return $this->redirectToRoute("afficheReservation",array('email'=> $reservation->getEmail()));}
                            else {
                                $complet="L'événement est complet !";
                            }
                           
                            }
                            return $this->renderForm("reservation/addR.html.twig",
                            array("f"=>$form ,'e'=> $complet));
                     }
                    #[Route('/updateReservation/{id}', name: 'updateReservation')]
                    public function updateReservation(ReservationRepository $repository,
                    $id,ManagerRegistry $doctrine,Request $request )
                    
                        { $complet='';
                        $reservation= $repository->find($id);
                        $form=$this->createForm(ReservationFormType::class,$reservation);
                        $form->handleRequest($request);
                        if($form->isSubmitted()){
                            
                            $nomevenement= $repository->find($reservation->getEvenements()->getId());
                            if ($nomevenement && $nomevenement->getNbrDePlaces()){
                            $nomevenement->setNbrDePlaces($nomevenement->getNbrDePlaces()-$reservation->getNombreDePlaceAReserver());
                            $em =$doctrine->getManager();
                            $em->flush();
                            
                        
                            return $this->redirectToRoute("afficheReservation",array('email'=> $reservation->getEmail()));}
                            else {
                                $complet="L'événement est complet !";
                            }
                              
                        }
                        
                        return $this->renderForm("reservation/addR.html.twig",
                            array("f"=>$form , 'e'=> $complet));
                    
                }  
                    
                    #[Route('/suppReservation/{id}', name: 'suppReservation')]
                    public function suppReservation($id,ReservationRepository $r,
                    ManagerRegistry $doctrine): Response
                    {
                        $reservation=$r->find($id);
                        $em =$doctrine->getManager();
                        $em->remove($reservation);
                        $em->flush();
                        return $this->redirectToRoute("afficheReservation",array('email'=> $reservation->getEmail()));}
}
