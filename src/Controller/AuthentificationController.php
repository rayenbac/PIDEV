<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Service\Utils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
  

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
    public function ForgotPassword(Request $request,ManagerRegistry $doctrine,UserPasswordEncoderInterface $encoder,Swift_Mailer $maile) {

        $entityManager = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST')) {
        $email=$request->request->get('email');
        $user=$doctrine->getRepository(User::class)->findOneBy(array('email' => $email));
        if ($user){
            $token = bin2hex(random_bytes(32));
            $user->setResetToken($encoder->encodePassword($user, $token));
            $user->setResetTokenExpiresAt(new \DateTimeImmutable('+1 hour'));
            $this->getDoctrine()->getManager()->flush();

            $message = (new Swift_Mail('Mot de password oublié'))
                ->setFrom('sportify0123@gmail.com')
                ->setTo($user->getEmail())
                ->setBody("<p> Bonjour</p> unde demande de réinitialisation de mot de passe a été effectuée. Veuillez cliquer sur le lien suivant ");

            //send mail
            $mailer->send($message);
           
        }
        
        


        }

        return $this->render('password/forgotpassword.html.twig', [
        ]);
    }
    
}
