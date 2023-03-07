<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

class DashboardController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }



    #[Route('/medecins/list', name: 'listeM')]
    public function listM(Request $request, ManagerRegistry $doctrine): Response
    {

        $medecins=$doctrine->getRepository(User::class)->findUsersByRole('ROLE_MEDECIN'); 

        return $this->render('admin/listM.html.twig', [
            'controller_name' => 'DashboardController',
            'medecins'=>$medecins
        ]);
    }

    #[Route('/medecins/edit/{id}', name: 'editmedecin')]
    public function editM($id,  Request $request, ManagerRegistry $doctrine): Response
    {

        $medecin=$doctrine->getRepository(User::class)->find($id);  
        if(!$medecin){
            return $this->redirectToRoute('error-404');
        }

        $entityManager = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST')) {
            $date = new \DateTimeImmutable(date('Y/m/d H:i:s'));
            $medecin->setFirstName($request->request->get('Firstname'));
            $medecin->setEmail($request->request->get('email'));
            $medecin->setLastname($request->request->get('Lastname'));
            $medecin->setAdresse($request->request->get('Adresse'));
            $medecin->setUpdatedAt($date);
            $entityManager->persist($medecin);
            $entityManager->flush();

            return $this->redirectToRoute('listeM');


        }
        return $this->render('admin/editM.html.twig', [
            'controller_name' => 'DashboardController',
            'medecin'=>$medecin
        ]);
    }

    #[Route('/medecins/gestion/{id}', name: 'gestionmedecin')]
    public function gestionM($id,  Request $request, ManagerRegistry $doctrine): Response
    {

        $medecin=$doctrine->getRepository(User::class)->find($id); 
        

        $entityManager = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST')) {
            $date = new \DateTimeImmutable(date('Y/m/d H:i:s'));
            $medecin->setUpdatedAt($date);
            $medecin->setStatus('1');
           
            $entityManager->persist($medecin);  
            $entityManager->flush();

            return $this->redirectToRoute('listeM');


        }
        return $this->render('admin/gestionM.html.twig', [
            'controller_name' => 'DashboardController',
            'medecin'=>$medecin
        ]);
    }

    #[Route('/medecins/delete/{id}', name: 'deletemedecin')]
    public function deleteM($id,ManagerRegistry $doctrine,Request $request)
    {
        $medecin = $doctrine->getRepository(User::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($medecin);
        $entityManager->flush();
        return $this->redirectToRoute('listeM');
    }



    #[Route('/membres/list', name: 'listeP')]
    public function listP(Request $request, ManagerRegistry $doctrine): Response
    {

        $patients=$doctrine->getRepository(User::class)->findUsersByRole('ROLE_PATIENT'); 

        return $this->render('admin/listP.html.twig', [
            'controller_name' => 'DashboardController',
            'patients'=>$patients
        ]);
    }

    #[Route('/membres/edit/{id}', name: 'editP')]
    public function editP($id,  Request $request, ManagerRegistry $doctrine): Response
    {

        $medecin=$doctrine->getRepository(User::class)->find($id); 
        if(!$medecin){
            return $this->redirectToRoute('error-404');
        }

        $entityManager = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST')) {
            $date = new \DateTimeImmutable(date('Y/m/d H:i:s'));
            $medecin->setFirstName($request->request->get('Firstname'));
            $medecin->setEmail($request->request->get('email'));
            $medecin->setLastname($request->request->get('Lastname'));
            $medecin->setAdresse($request->request->get('adresse'));
            $medecin->setUpdatedAt($date);
            $entityManager->persist($medecin);
            $entityManager->flush();

            return $this->redirectToRoute('listeP');



        }
        return $this->render('admin/editP.html.twig', [
            'controller_name' => 'DashboardController',
            'medecin'=>$medecin
        ]);
    }

    #[Route('/membres/delete/{id}', name: 'deleteP')]
    public function deleteP($id,ManagerRegistry $doctrine,Request $request)
    {
        $patient = $doctrine->getRepository(User::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($patient);
        $entityManager->flush();
        return $this->redirectToRoute('listeP');
    }

    #[Route('/notification', name: 'notification')]
    public function notification(ManagerRegistry $doctrine,Request $request)
    {

        if ($this->isGranted('ROLE_ADMIN')) {

            $notifications=$doctrine->getRepository(Notification::class)->findBy(array('Status' => '0' ));
        
        }
        return $this->render('notification/index.html.twig', [
            'controller' => 'admin',
            'notifs'=>$notifications,


        ]);

    }



    #[Route('/notification/delete', name: 'deletenotif')]
    public function deleteNotif(ManagerRegistry $doctrine,Request $request)
    {

        if ($this->isGranted('ROLE_ADMIN')) {

            $notifications=$doctrine->getRepository(Notification::class)->findAll();
            $entityManager = $this->getDoctrine()->getManager();

            foreach ($notifications as $notification) {
                $entityManager->remove($notification);
            }
            
            $entityManager->flush();
            
            return $this->redirectToRoute('admin');
        
        }
        return $this->render('notification/index.html.twig', [
            'controller' => 'admin',
            'notifs'=>$notifications,


        ]);

    }

    #[Route('/user/show/{email}', name: 'editPP')]
    public function editPP($email,  Request $request, ManagerRegistry $doctrine): Response
    {

        $medecin=$doctrine->getRepository(User::class)->findByEmail($email);
        if(!$medecin){
            return $this->redirectToRoute('error-404');
        }

        $entityManager = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST')) {
            $date = new \DateTimeImmutable(date('Y/m/d H:i:s'));
            $medecin->setFirstName($request->request->get('Firstname'));
            $medecin->setEmail($request->request->get('email'));
            $medecin->setLastname($request->request->get('Lastname'));
            $medecin->setAdresse($request->request->get('adresse'));
            $medecin->setUpdatedAt($date);
            $entityManager->persist($medecin);
            $entityManager->flush();

            return $this->redirectToRoute('listeP');



        }
        return $this->render('admin/editPP.html.twig', [
            'controller_name' => 'DashboardController',
            'user'=>$medecin
        ]);
    }

}
