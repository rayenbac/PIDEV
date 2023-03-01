<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;


class AuthentificationController extends AbstractController
{
    #[Route('/authentification', name: 'login')]
    public function index(): Response
    {
        return $this->render('login.html.twig', [
            'controller_name' => 'AuthentificationController',
        ]);
    }



    #[Route('/forgotPassword', name: 'forgotPassword')]
    public function ForgotPassword(Request $request,ManagerRegistry $doctrine) {

        $entityManager = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST')) {
        $email=$request->request->get('email');
        $user=$doctrine->getRepository(User::class)->findOneBy(array('email' => $email));
        dd($user);
        if ($user){
            //$token=$this->utils->generatePassword(16);
            //$user->setToken($token);
            //$entityManager->persist($user);
            //$entityManager->flush();
            //$this->utils->sendEmail($user,'RESETPASS','');
            //$this->addFlash('success', 'Un email a été envoyé à votre adresse !');
        }
        
        


        }

        return $this->render('password/forgotpassword.html.twig', [
        ]);
    }
}
