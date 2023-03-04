<?php

namespace App\Controller;

use App\Repository\RendezVousRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\TwilioService;
use Symfony\Component\HttpFoundation\Response;

class MyController extends AbstractController
{
    private $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }
    #[Route('/sms', name: 'sms')]

    public function sendSms(TwilioService $twilioService,RendezVousRepository $rendezVousRepository)
    {
        $toPhoneNumber = '+21654300673'; // remplacer par le numéro de téléphone réel
        $message = 'votre rendez vous est créé avec succès ';

        $twilioService->sendSms($toPhoneNumber, $message);
        return $this->render('rendez_vous/index.html.twig', [
            'rendez_vouses' => $rendezVousRepository->findAll(),
        ]);

        // Retournez une réponse Symfony si nécessaire
    }
}
