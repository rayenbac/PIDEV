<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Swift_Mailer;
use Swift_Message;

class ProfileController extends AbstractController
{
   
    
    #[Route('/profile', name: 'profile')]
    public function index(Request $request,ManagerRegistry $doctrine)
    {

        $user= $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST')) {
        $date = new \DateTimeImmutable(date('Y/m/d H:i:s'));
        $birthdate=new \DateTimeImmutable(date($request->request->get('birthdate')));
        $user->setGender($request->request->get('gender'));
        $user->setEmail($request->request->get('email'));
        $user->setFirstname($request->request->get('firstname'));
        $user->setLastname($request->request->get('lastname'));
        $user->setBirthdate($birthdate);
        $user->setPhoneNumber($request->request->get('phone'));
        $user->setAdresse($request->request->get('adresse'));
        $user->setUpdatedAt($date);
        $entityManager->persist($user);
        $entityManager->flush();



        }

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'user'=>$user
        ]);
    }

    #[Route('/profile/password', name: 'password')]
    public function newPassword(Request $request,UserPasswordEncoderInterface $encoder,ManagerRegistry $doctrine,Swift_Mailer $mailer )
        {   
        $user= $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST')) {
            $password=$request->get('password');
           
            $message = (new Swift_Message('Nouveau mot de passe'))
            ->setFrom('sportify0123@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(" voici votre nouveau mot de passe:".$password,
            "text/html");
            $mailer->send($message);
            $user->setPassword($encoder->encodePassword($user,$password));
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success','un email a été envoyé a votre adresse  ');

        
        }

        return $this->redirectToRoute('profile');
        

        }


        
}
