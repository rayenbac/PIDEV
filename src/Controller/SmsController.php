<?php

namespace App\Controller;

use App\Repository\MoodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\TwilioService;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Mood;


class MyController extends AbstractController
{
    private $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }
    #[Route('/sms', name: 'sms')]

    public function sendSms(TwilioService $twilioService, MoodRepository $MoodRepository)
    {
        $toPhoneNumber = '+21624660566'; // remplacer par le numéro de téléphone réel
        $message = 'Votre Mood est enregistrée avec succées ';

        $twilioService->sendSms($toPhoneNumber, $message);
        $c = $this->getDoctrine()->getRepository(Mood::Class)->findAll();
        //utiliser la fonction findAll()
        //$c=$r->findAll();
        return $this->render('mood/index.html.twig', [
            'm' => $c
        ]);
    }
    // Retournez une réponse Symfony si nécessaire
}
