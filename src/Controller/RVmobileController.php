<?php

namespace App\Controller;

use App\Entity\Cabinet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MedecinRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Entity\Medecin;
use App\Entity\RendezVous;
use Symfony\Component\HttpFoundation\Request;

class RVmobileController extends AbstractController
{
    #[Route('/mobile/medecin', name: 'lister_medecin')]
    public function liste(MedecinRepository $rendezVousRepository,NormalizerInterface $normalizer)
    {
        $users = $this->getDoctrine()->getRepository(Medecin::class)->findAll();
        $medecin = $normalizer->normalize($users,'json',['groups'=>"medecins"]);
        $json = json_encode($medecin);
        return new Response($json);
    
    }

    #[Route('/mobile/medecin/{id}', name: 'medecinbyid')]
    public function listeId(MedecinRepository $rendezVousRepository,NormalizerInterface $normalizer,$id)
    {
        $users = $this->getDoctrine()->getRepository(Medecin::class)->find($id);
        $medecin = $normalizer->normalize($users,'json',['groups'=>"medecins"]);
        $json = json_encode($medecin);
        return new Response($json);
    
    }
    #[Route('/mobile/rendezvous', name: 'rendezvous')]
    public function rendezvous(MedecinRepository $rendezVousRepository,NormalizerInterface $normalizer,)
    {
        $rendezvous = $this->getDoctrine()->getRepository(RendezVous::class)->findAll();
        $s = $normalizer->normalize($rendezvous,'json',['groups'=>"rendezvous"]);
        $json = json_encode($s);
        return new Response($json);
    
    }

    #[Route('/mobile/rendezvous/{id}', name: 'rendezvousid')]
    public function rendezvousId(MedecinRepository $rendezVousRepository,NormalizerInterface $normalizer,$id)
    {
        $rendezvous = $this->getDoctrine()->getRepository(RendezVous::class)->find($id);
        $s = $normalizer->normalize($rendezvous,'json',['groups'=>"rendezvous"]);
        $json = json_encode($s);
        return new Response($json);
    
    }
    #[Route('/mobile/rendezvous/add', name: 'rendezvousadd')]
    public function rendezvousajoute(Request $req,NormalizerInterface $normalizer)
    {
        //$medecin = $this->getDoctrine()->getRepository(Medecin::class)->find($req->get('medecin_id'));
        //$cabinet = $this->getDoctrine()->getRepository(Cabinet::class)->find($req->get('cabinet_id'));
        $em= $this->getDoctrine()->getManager();
        $rendezvous = new RendezVous();
        $rendezvous->setNom($req->get('nom'));
        $rendezvous->setPrenom($req->get('prenom'));
        $rendezvous->setCause($req->get('cause'));
        $rendezvous->setDateRV($req->get('dateRV'));
        $rendezvous->setDescription($req->get('description'));

        //$rendezvous->setMedecin($medecin);
       // $rendezvous->setCabinet($cabinet);


        $em->persist($rendezvous);
        $em->flush();



        $s = $normalizer->normalize($rendezvous,'json',['groups'=>"rendezvous"]);
        $json = json_encode($s);
        return new Response($json);
    
    }

   

}
