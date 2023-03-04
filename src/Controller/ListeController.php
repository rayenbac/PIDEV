<?php

namespace App\Controller;

use App\Entity\Medecin;
use App\Entity\RendezVous;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListeController extends AbstractController
{
    #[Route('/liste_medecin', name: 'app_liste')]
    public function list(): Response
    {
        $users = $this->getDoctrine()->getRepository(Medecin::class)->findAll();
        
        return $this->render('liste/list.html.twig', [
            'medecin' => $users,
        ]);
    }

    #[Route('/liste_rendezvous', name: 'liste_rendezvous')]
    public function rendezvous_liste(): Response
    {
        $rv = $this->getDoctrine()->getRepository(RendezVous::class)->findAll();

        return $this->render('liste/listRV.html.twig', [
            'rv' => $rv,
        ]);
    }

    #[Route('/dashboard/rendezvous', name: 'rendezvousA')]
    public function rendezvous_listeA(): Response
    {
        $rv = $this->getDoctrine()->getRepository(RendezVous::class)->findAll();

        return $this->render('liste/listeR.html.twig', [
            'rv' => $rv,
        ]);
    }


}