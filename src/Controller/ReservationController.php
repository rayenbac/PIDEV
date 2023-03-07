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
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

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
     
    public function addR(ManagerRegistry $doctrine, Request $request, EvenementsRepository $repository, MailerInterface $mailer)
    {
        $complet='';
        $reservation = new Reservation();
        $form = $this->createForm(ReservationFormType::class, $reservation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $nomevenement = $repository->find($reservation->getEvenements()->getId());
            if ($nomevenement->getNbrDePlaces()) {
                $nombreDePlaces = $reservation->getNombreDePlaceAReserver();
                $nomevenement->setNbrDePlaces($nomevenement->getNbrDePlaces() - $nombreDePlaces);
    
                $em = $doctrine->getManager();
                $em->persist($reservation);
                $em->flush();
    
                // Envoie de l'email
                $email = (new Email())
                    ->from('pidevtest7@gmail.com')
                    ->to($reservation->getEmail())
                    ->subject('Confirmation de réservation')
                    ->text('Bonjour '  . ', votre réservation pour ' . $nombreDePlaces . ' place(s) pour l\'événement ' . $nomevenement->getnomevenement() . ' a été confirmée.');
    
                $mailer->send($email);
    
                return $this->redirectToRoute('afficheReservation', ['email' => $reservation->getEmail()]);
            } else {
                $complet = "L'événement est complet !";
            }
        }
    
        return $this->renderForm('reservation/addR.html.twig', ['f' => $form, 'e' => $complet]);
    }
                    #[Route('/updateReservation/{id}', name: 'updateReservation')]
                    public function updateReservation(ReservationRepository $repository,
                    $id,ManagerRegistry $doctrine,Request $request,EvenementsRepository $r )
                    
                        { $complet='';
                        $reservation= $repository->find($id);
                        $nbr=$reservation->getNombreDePlaceAReserver();
                        $form=$this->createForm(ReservationFormType::class,$reservation);
                        $form->handleRequest($request);
                        if($form->isSubmitted()){
                            
                            $nomevenement= $r->find($reservation->getEvenements()->getId());
                            if ($nomevenement && $nomevenement->getNbrDePlaces()){
                            $nomevenement->setNbrDePlaces($nomevenement->getNbrDePlaces()-$reservation->getNombreDePlaceAReserver()+$nbr);
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
                    ManagerRegistry $doctrine,EvenementsRepository $rep ): Response
                    {
                        $reservation=$r->find($id);
                        $evenement=$rep->find($reservation->getEvenements()->getId());
                        $evenement->setNbrDePlaces($evenement->getNbrDePlaces()+$reservation->getNombreDePlaceAReserver());
                        $em =$doctrine->getManager();
                        $em->remove($reservation);
                        $em->flush();
                        return $this->redirectToRoute("afficheReservation",array('email'=> $reservation->getEmail()));}



//ApiCode : 



#[Route('/ApiAfficheR/{email}', name: 'ApiAfficheR')]
    public function ApiAfficheR(NormalizerInterface $normalizer, ManagerRegistry $doctrine, ReservationRepository $reservationRepository, $email): Response
    {
        $reservation = $reservationRepository->findBy(array('Email'=>$email));
        $normalizedReservation = $normalizer->normalize($reservation, 'json', ['groups' => 'info']);
        $json = json_encode($normalizedReservation);
        return new Response($json);
    }

                    


 #[Route('/ApiAddR', name: 'ApiAddR')]
    public function ApiAddR(ManagerRegistry $doctrine,Request $request, SluggerInterface $slugger)
        {$reservation= new Reservation();
                    $form=$this->createForm(ReservationFormType::class,$reservation);
                                       
                                        $reservation->setNombreDePlaceAReserver($request->get('NombreDePlaceAReserver'));
                                        $reservation->setEmail($request->get('Email'));
                                        //     Nom évenement à réccupérer      
                                           $em =$doctrine->getManager() ;
                                           $em->persist($reservation);
                                           $em->flush();
                                           $jsonContent = $Normalizer->normalize($reservation, 'json', ['groups' => 'info']);
                                           return new Response(json_encode($jsonContent));
                                    }
                                

#[Route('/ApiUpdateR/{id}', name: 'ApiUpdateR')]
public function ApiUpdateR(ReservationRepository $repository,
                    $id,ManagerRegistry $doctrine,Request $request,EvenementsRepository $r )
                    
                        { 
                        $reservation= $repository->find($id);
                        $form=$this->createForm(ReservationFormType::class,$reservation);
                        $reservation->setNombreDePlaceAReserver($request->get('NombreDePlaceAReserver'));
                        $reservation->setEmail($request->get('Email'));
                        //     Nom évenement à réccupérer  
                        
                        $em = $doctrine->getManager();
                        $em->persist($reservation);
                        $em->flush();
                        $json = $normalizer->normalize($product, 'json', ['groups' => 'info']);
                        return new Response(json_encode($json));
                            
                        
                           
                        }

                        #[Route('/ApiSuppR/{id}', name: 'ApiSuppR')]
                        public function ApiSuppR(NormalizerInterface $normalizer, ManagerRegistry $doctrine, ReservationRepository $reservationRepository, $id,
                        EvenementsRepository $rep ): Response
                        {
                            $reservation = $reservationRepository->find($id);
                            $evenement=$rep->find($reservation->getEvenements()->getId());
                            $evenement->setNbrDePlaces($evenement->getNbrDePlaces()+$reservation->getNombreDePlaceAReserver());
                            $em = $doctrine->getManager();
                            $em->remove($reservation);
                            $em->flush();
                            $json = $normalizer->normalize($reservation, 'json', ['groups' => 'info']);
                            $response = new Response("Reservation supprimée avec succès : " . json_encode($json));
                            $response->headers->set('Content-Type', 'application/json');
                            return $response;
                        }
                        
                    
                }  
                            
        

