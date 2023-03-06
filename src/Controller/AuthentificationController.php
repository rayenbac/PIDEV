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
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;  

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
    public function ForgotPassword(Request $request,ManagerRegistry $doctrine,UserPasswordEncoderInterface $encoder,Swift_Mailer $mailer) {

        $entityManager = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST')) {
        $email=$request->request->get('email');
        $user=$doctrine->getRepository(User::class)->findOneBy(array('email' => $email));
        if ($user){
            $token = bin2hex(random_bytes(32));
            $user->setResetToken($token);
            $user->setResetTokenExpiresAt(new \DateTimeImmutable('+1 hour'));
            $this->getDoctrine()->getManager()->flush();

            $url = $this->generateUrl('app_reset_password',array('token'=>$token),UrlGeneratorInterface::ABSOLUTE_URL);
            $message = (new Swift_Message('Mot de password oublié'))
                ->setFrom('sportify0123@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(" <p>Bonjour</p> une demande de réinitialisation de mot de passe a été effectuée. Veuillez cliquer sur le lien suivant :".$url,
                "text/html");

            //send mail
            $mailer->send($message);
            $this->addFlash('success','E-mail  de réinitialisation du mot de passe envoyé ');
        }
        
        if(!$user) {
            $this->addFlash('error','cette adresse n\'existe pas');
            return $this->redirectToRoute("forgotPassword");

        }


        }

        return $this->render('password/forgotpassword.html.twig', [
        ]);
    }


    #[Route('/resetPassword/{token}', name: 'app_reset_password')]
    public function resetpassword(Request $request,string $token,ManagerRegistry $doctrine, UserPasswordEncoderInterface  $passwordEncoder)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user =$doctrine->getRepository(User::class)->findOneBy(array('resetToken'=>$token));
        if($user == null ) {
            $this->addFlash('error','TOKEN INCONNU');
            return $this->redirectToRoute("app_login");
       
        }
        if($request->isMethod('POST')) {
            $password1=$request->request->get('password');
            $password2=$request->request->get('password1');
            if ($password1 == $password2) {
            $user->setPassword($passwordEncoder->encodePassword($user,$password1));
            $entityManager->persist($user);
            $entityManager->flush();
            
            $this->addFlash('success','Mot de passe mis à jour :');
            return $this->redirectToRoute("app_login");
            }
        }
        else {
            return $this->render("password/resetpassword.html.twig",['token'=>$token]);

        }
    }
}   
