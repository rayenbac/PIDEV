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
use App\Repository\RerservationRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;




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
    public function afficheRAdmin(RerservationRepository $repository): Response
    {
        // $e=$this->getDoctrine()->getRepository(Reservation::Class);

        $info = $repository->findAll();

        return $this->render('reservation/afficheRAdmin.html.twig', [
            'reservation' => $info,
        ]);
    }
    #[Route('/afficheReservation/{email}', name: 'afficheReservation')]
    public function afficheReservation(RerservationRepository $repository, $email): Response
    {
        // $e=$this->getDoctrine()->getRepository(Reservation::Class);

        $info = $repository->findBy(array('Email' => $email));

        return $this->render('reservation/afficheReservation.html.twig', [
            'reservation' => $info,
        ]);
    }
    #[Route('/confirmation', name: 'confirmation')]
    public function confirmation(RerservationRepository  $r): Response
    {
        //$f=$r->find($id);
        //$fjson=$serializer->serialize($f, 'json', ['groups' => "suppliers"]);
        //$lien=$f->getWebsite();
        //$img=$f->getImg();
        $qrCode = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data("cette personne est autorisée de faire partie de l'événement!")
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->labelText("")
            ->labelFont(new NotoSans(20))
            ->labelAlignment(new LabelAlignmentCenter())
            //->logoPath('public/uploads/fournisseur/nike-63fe736cba9d1.png')
            ->build();

        return $this->render('reservation/confirmation.html.twig', [
            'qr' => $qrCode->getDataUri()
        ]);
    }




    #[Route('/addR', name: 'addR')]

    public function addR(ManagerRegistry $doctrine, Request $request, EvenementsRepository $repository, MailerInterface $mailer)
    {
        $complet = '';
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

                //mail 

                $email = (new Email())

                    ->from('echkiliboucha@gmail.com')
                    ->to($form->get('Email')->getData())
                    //->cc('cc@example.com')
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    //->priority(Email::PRIORITY_HIGH)
                    ->subject('Réservation confrimée !')
                    ->text('Votre réservation est confirmée ')
                    ->html('<p>Merci de nous faire confiance </p>');



                $mailer->send($email);






                return $this->redirectToRoute('afficheReservation', ['email' => $reservation->getEmail()]);
            } else {
                $complet = "L'événement est complet !";
            }
        }

        return $this->renderForm('reservation/addR.html.twig', ['f' => $form, 'e' => $complet]);
    }
    #[Route('/updateReservation/{id}', name: 'updateReservation')]
    public function updateReservation(
        RerservationRepository $repository,
        $id,
        ManagerRegistry $doctrine,
        Request $request,
        EvenementsRepository $r
    ) {
        $complet = '';
        $reservation = $repository->find($id);
        $nbr = $reservation->getNombreDePlaceAReserver();
        $form = $this->createForm(ReservationFormType::class, $reservation);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $nomevenement = $r->find($reservation->getEvenements()->getId());
            if ($nomevenement && $nomevenement->getNbrDePlaces()) {
                $nomevenement->setNbrDePlaces($nomevenement->getNbrDePlaces() - $reservation->getNombreDePlaceAReserver() + $nbr);
                $em = $doctrine->getManager();
                $em->flush();


                return $this->redirectToRoute("afficheReservation", array('email' => $reservation->getEmail()));
            } else {
                $complet = "L'événement est complet !";
            }
        }

        return $this->renderForm(
            "reservation/addR.html.twig",
            array("f" => $form, 'e' => $complet)
        );
    }

    #[Route('/suppReservation/{id}', name: 'suppReservation')]
    public function suppReservation(
        $id,
        RerservationRepository $r,
        ManagerRegistry $doctrine,
        EvenementsRepository $rep
    ): Response {
        $reservation = $r->find($id);
        $evenement = $rep->find($reservation->getEvenements()->getId());
        $evenement->setNbrDePlaces($evenement->getNbrDePlaces() + $reservation->getNombreDePlaceAReserver());
        $em = $doctrine->getManager();
        $em->remove($reservation);
        $em->flush();
        return $this->redirectToRoute("afficheReservation", array('email' => $reservation->getEmail()));
    }



    //ApiCode : 



    #[Route('/ApiAfficheR/{email}', name: 'ApiAfficheR')]
    public function ApiAfficheR(NormalizerInterface $normalizer, ManagerRegistry $doctrine, RerservationRepository $reservationRepository, $email): Response
    {
        $reservation = $reservationRepository->findBy(array('Email' => $email));
        $normalizedReservation = array_map(function ($reservation) use ($normalizer) {
            $normalizedReservation = $normalizer->normalize($reservation, 'json', ['groups' => 'info']);
            $event_id = $reservation->getId() ? $reservation->getEvenements()->getId() : null;
            $normalizedReservation['event'] = $event_id;
            return $normalizedReservation;
        }, $reservation);


        $json = json_encode($normalizedReservation, JSON_PRETTY_PRINT);
        return new Response($json, 200, ['Content-Type' => 'application/json']);
    }




    #[Route('/ApiAddR', name: 'ApiAddR')]
    public function ApiAddR(ManagerRegistry $doctrine, Request $request, NormalizerInterface $normalizer, EvenementsRepository $repository)
    {
        $complet = '';
        $reservation = new Reservation();


        $reservation->setNombreDePlaceAReserver($request->get('NombreDePlaceAReserver'));
        $reservation->setEmail($request->get('Email'));
        $event = $repository->find($request->get('id'));
        $reservation->setEvenements($event);

        //     Nom évenement à réccupérer      
        $em = $doctrine->getManager();
        $em->persist($reservation);
        $em->flush();

        $jsonContent = $normalizer->normalize($reservation, 'json', ['groups' => 'info']);
        return new Response(json_encode($jsonContent));
    }


    #[Route('/ApiUpdateR/{id}', name: 'ApiUpdateR')]
    public function ApiUpdateR(
        RerservationRepository $repository,
        $id,
        ManagerRegistry $doctrine,
        Request $request,
        EvenementsRepository $r,
        NormalizerInterface $normalizer
    ) {
        $reservation = $repository->find($id);
        $reservation->setNombreDePlaceAReserver($request->get('NombreDePlaceAReserver'));
        $reservation->setEmail($request->get('Email'));
        //     Nom évenement à réccupérer  

        $em = $doctrine->getManager();
        $em->persist($reservation);
        $em->flush();
        $json = $normalizer->normalize($reservation, 'json', ['groups' => 'info']);
        return new Response(json_encode($json));
    }

    #[Route('/ApiSuppR/{id}', name: 'ApiSuppR')]
    public function ApiSuppR(
        NormalizerInterface $normalizer,
        ManagerRegistry $doctrine,
        RerservationRepository $reservationRepository,
        $id,
        EvenementsRepository $rep
    ): Response {
        $reservation = $reservationRepository->find($id);
        $evenement = $rep->find($reservation->getEvenements()->getId());
        $evenement->setNbrDePlaces($evenement->getNbrDePlaces() + $reservation->getNombreDePlaceAReserver());
        $em = $doctrine->getManager();
        $em->remove($reservation);
        $em->flush();
        $json = $normalizer->normalize($reservation, 'json', ['groups' => 'info']);
        $response = new Response("Reservation supprimée avec succès : " . json_encode($json));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
