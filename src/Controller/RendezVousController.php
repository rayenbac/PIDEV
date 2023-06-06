<?php

namespace App\Controller;

use App\Entity\RendezVous;

use App\Entity\Medecin;
use App\Form\RendezVousType;
use App\Repository\RendezVousRepository;
use App\Repository\MedecinRepository;
use App\Repository\CabinetRepository;
use App\Entity\Cabinet;
use App\Service\TwilioService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Swift_Mailer;
use Swift_Message;

#[Route('/rendez/vous')]
class RendezVousController extends AbstractController

{
    #[Route('/', name: 'app_rendez_vous_index', methods: ['GET'])]
    public function index(RendezVousRepository $rendezVousRepository): Response
    {
        return $this->render('rendez_vous/index.html.twig', [
            'rendez_vouses' => $rendezVousRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_rendez_vous_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RendezVousRepository $rendezVousRepository): Response
    {
        $rendezVou = new RendezVous();
        $form = $this->createForm(RendezVousType::class, $rendezVou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rendezVousRepository->save($rendezVou, true);

            return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rendez_vous/new.html.twig', [
            'rendez_vou' => $rendezVou,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rendez_vous_show', methods: ['GET'])]
    public function show(RendezVous $rendezVou): Response
    {
        return $this->render('rendez_vous/show.html.twig', [
            'rendez_vou' => $rendezVou,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rendez_vous_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RendezVous $rendezVou, RendezVousRepository $rendezVousRepository): Response
    {
        $form = $this->createForm(RendezVousType::class, $rendezVou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rendezVousRepository->save($rendezVou, true);

            return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rendez_vous/edit.html.twig', [
            'rendez_vou' => $rendezVou,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rendez_vous_delete', methods: ['POST'])]
    public function delete(Request $request, RendezVous $rendezVou, RendezVousRepository $rendezVousRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $rendezVou->getId(), $request->request->get('_token'))) {
            $rendezVousRepository->remove($rendezVou, true); //vérifie que le CSRF (Cross-Site Request Forgery) est valide pour empêcher les attaques de type CSRF. Si le CSRF est valide, la méthode continue.
        }

        return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER); // indique le code de réponse HTTP à utiliser pour la redirection.
    }

    #[Route('/appointments/create/{medecinId}', name: 'appointments')]


    public function create(Request $request, int $medecinId, MedecinRepository $medecinRepository, RendezVousRepository $rvRepository, CabinetRepository $cabinetRepository, TwilioService $twilioService, Swift_Mailer $mailer)
    {
        // Get the doctor with the given ID
        $medecin = $medecinRepository->find($medecinId);
        $cabinet = $cabinetRepository->find($medecin->getCabinet()->getId()); //Notez que j'ai ajouté une injection de dépendance pour le CabinetRepository et que j'ai utilisé la méthode getCabinet() pour récupérer la cabinet associée au médecin.
        if (!$medecin) {
            throw $this->createNotFoundException('Doctor not found');
        }

        // Create a new appointment and set its doctor
        $rendezVou = new RendezVous();
        $rendezVou->setMedecin($medecin);
        $rendezVou->setCabinet($cabinet);


        $form = $this->createForm(RendezVousType::class, $rendezVou);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rendezVou);
            $entityManager->flush();


            return $this->redirectToRoute('app_rendez_vous_show', [
                'id' => $rendezVou->getId()
            ]);
        }


        return $this->renderForm('rendez_vous/new.html.twig', [
            'rendez_vou' => $rendezVou,
            'form' => $form,

        ]);
    }
}
