<?php

namespace App\Controller;



use App\Entity\Consultation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

class ConsultationController extends AbstractController
{
    #[Route('/consultation', name:'consultation')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {

        $consultations=$doctrine->getRepository(Consultation::class)->findAll();

        return $this->render('consultation/index.html.twig', [
            'controller_name' => 'ConsultationController',
            'consultations'=>$consultations
        ]);
    }

    #[Route('/add/consultation', name:'addConsultation')]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        if ($request->isMethod('POST')) {

            $consultation = new consultation();
            $dateNow = new \DateTime(date('Y-m-d H:i:s'));
            $date=new \DateTime(date($request->request->get('date')));
            $consultation->setTitre($request->request->get('title'));
            $consultation->setDescription($request->request->get('description'));
            $consultation->setDate($date);
            $consultation->setCreatedAt($dateNow);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($consultation);
            $entityManager->flush();
            return $this->redirectToRoute('consultation'); 
        }
        return $this->render('consultation/add.html.twig', [
            'controller_name' => 'ConsultationController',
        ]);
    }

    #[Route('/edit/consultation/{id}', name:'editConsultation')]
    public function edit($id, Request $request,ManagerRegistry $doctrine): Response
    {

        $consultation=$doctrine->getRepository(Consultation::class)->find($id);
        if ($request->isMethod('POST')) {   

            $dateNow = new \DateTime(date('Y-m-d H:i:s'));
            $date=new \DateTime(date($request->request->get('date')));
            $consultation->setTitre($request->request->get('title'));
            $consultation->setDescription($request->request->get('description'));
            $consultation->setDate($date);
            $consultation->setCreatedAt($dateNow);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($consultation);
            $entityManager->flush();
            return $this->redirectToRoute('consultation'); 
        }
        return $this->render('consultation/edit.html.twig', [
            'controller_name' => 'ConsultationController',
            'consultation'=>$consultation
        ]);
    }

    #[Route('/delete/consultation/{id}', name:'deleteConsultation')]
    public function delete($id, Request $request,ManagerRegistry $doctrine): Response
    {
        $consultation=$doctrine->getRepository(Consultation::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($consultation);
        $entityManager->flush();
        return $this->redirectToRoute('consultation');


    }

}
