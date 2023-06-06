<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MedecinRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Entity\Medecin;
use App\Entity\RendezVous;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Consultation;

class RVmobileController extends AbstractController
{
    #[Route('/mobile/medecin', name: 'lister_medecin')]
    public function liste(MedecinRepository $rendezVousRepository, NormalizerInterface $normalizer)
    {
        $users = $this->getDoctrine()->getRepository(Medecin::class)->findAll();
        $medecin = $normalizer->normalize($users, 'json', ['groups' => "medecins"]);
        $json = json_encode($medecin);
        return new Response($json);
    }

    #[Route('/mobile/medecin/{id}', name: 'medecinbyid')]
    public function listeId(MedecinRepository $rendezVousRepository, NormalizerInterface $normalizer, $id)
    {
        $users = $this->getDoctrine()->getRepository(Medecin::class)->find($id);
        $medecin = $normalizer->normalize($users, 'json', ['groups' => "medecins"]);
        $json = json_encode($medecin);
        return new Response($json);
    }
    #[Route('/mobile/rendezvous', name: 'rendezvous')]
    public function rendezvous(MedecinRepository $rendezVousRepository, NormalizerInterface $normalizer,)
    {
        $rendezvous = $this->getDoctrine()->getRepository(RendezVous::class)->findAll();
        $s = $normalizer->normalize($rendezvous, 'json', ['groups' => "rendezvous"]);
        $json = json_encode($s);
        return new Response($json);
    }

    #[Route('/mobile/rendezvous/{id}', name: 'rendezvousid')]
    public function rendezvousId(MedecinRepository $rendezVousRepository, NormalizerInterface $normalizer, $id)
    {
        $rendezvous = $this->getDoctrine()->getRepository(RendezVous::class)->find($id);
        $s = $normalizer->normalize($rendezvous, 'json', ['groups' => "rendezvous"]);
        $json = json_encode($s);
        return new Response($json);
    }
    #[Route('/mobile/rendezvous/add', name: 'rendezvousadd')]
    public function rendezvousajoute(Request $req, NormalizerInterface $normalizer)
    {
        //$medecin = $this->getDoctrine()->getRepository(Medecin::class)->find($req->get('medecin_id'));
        //$cabinet = $this->getDoctrine()->getRepository(Cabinet::class)->find($req->get('cabinet_id'));
        $em = $this->getDoctrine()->getManager();
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



        $s = $normalizer->normalize($rendezvous, 'json', ['groups' => "rendezvous"]);
        $json = json_encode($s);
        return new Response($json);
    }


    #[Route('/mobile/consultaion/{id}', name: 'consid')]
    public function consultationId(NormalizerInterface $normalizer, $id)
    {

        $rendezvous = $this->getDoctrine()->getRepository(Consultation::class)->find($id);
        $s = $normalizer->normalize($rendezvous, 'json', ['groups' => "consultation"]);
        $json = json_encode($s);
        return new Response($json);
    }
    #[Route('/mobile/consultation', name: 'consultation')]
    public function consultationshow(NormalizerInterface $normalizer,)
    {
        $rendezvous = $this->getDoctrine()->getRepository(Consultation::class)->findAll();
        $s = $normalizer->normalize($rendezvous, 'json', ['groups' => "consultation"]);
        $json = json_encode($s);
        return new Response($json);
    }

    #[Route('/mobile/consultation/add', name: 'consultaionadd')]
    public function consajoute(Request $req, NormalizerInterface $normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $rendezvous = new Consultation();
        $rendezvous->setNom($req->get('nom'));
        $rendezvous->setPrenom($req->get('prenom'));
        $rendezvous->setCause($req->get('cause'));
        $rendezvous->setDescription($req->get('description'));
        $rendezvous->setDate($req->get('date'));
        $rendezvous->setMedecin($req->get('medecin'));
        $rendezvous->setCabinet($req->get('cabinet'));


        $em->persist($rendezvous);
        $em->flush();



        $s = $normalizer->normalize($rendezvous, 'json', ['groups' => "consultation"]);
        $json = json_encode($s);
        return new Response($json);
    }
    #[Route('/mobile/upconsultation/{id}', name: 'up_consultaion')]
    public function up(Request $req, $id, NormalizerInterface $normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $rendezvous = $this->getDoctrine()->getRepository(Consultation::class)->find($id);
        $rendezvous->setNom($req->get('nom'));
        $rendezvous->setPrenom($req->get('prenom'));
        $rendezvous->setCause($req->get('cause'));
        $rendezvous->setDescription($req->get('description'));
        $rendezvous->setDate($req->get('date'));
        $rendezvous->setMedecin($req->get('medecin'));
        $rendezvous->setCabinet($req->get('cabinet'));
        $em->flush();

        $json = $normalizer->normalize($rendezvous, 'json', ['groups' => 'students']);
        return new Response("mrigel" . json_encode($json));
    }
    #[Route('/mobile/delconsultation/{id}', name: 'del_consultaion')]
    public function del(Request $req, $id, NormalizerInterface $normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $del = $em->getRepository(Consultation::class)->find($id);
        $em->remove($del);
        $em->flush();
        $json = $normalizer->normalize($del, 'json', ['groups' => 'consultation']);
        return new Response("mrigel" . json_encode($json));
    }
}
