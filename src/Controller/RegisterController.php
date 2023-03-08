<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\Notification;
use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Swift_Mailer;
use Swift_Message;



class RegisterController extends AbstractController
{

    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;
    }



    #[Route('/inscription', name: 'register')]
    public function index(Request $request, UserPasswordEncoderInterface $encoder, SluggerInterface $slugger, Swift_Mailer $mailer): Response
    {


        $user = new user();
        $form = $this->createForm(RegisterType::class, $user);


        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $date = new \DateTimeImmutable(date('Y/m/d H:i:s'));
                $user = $form->getData();
                $email = $form->getData()->getEmail();

                $user->setCreatedAt($date);
                $password = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
                $file = $form->get('file')->getData();

                if ($file) {
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $file->move(
                            $this->getParameter('attachement_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents


                    $user->setFile($newFilename);
                }
                $user->setStatus('0');
                $doctrine = $this->getDoctrine()->getManager();
                $doctrine->persist($user);

                $notification = new Notification();
                $notification->setEmailUser($form->get('email')->getData());
                $notification->setText('Nouveau utilisateur');
                $notification->setStatus(0);
                if ($form->get('Roles')->getData() == ['ROLE_PATIENT']) {
                    $notification->setCode('PATIENT');
                }
                if ($form->get('Roles')->getData() == ['ROLE_MEDECIN']) {
                    $notification->setCode('MEDECIN');
                }

                $user->addNotif($notification);
                $doctrine->persist($notification);
                $doctrine->flush();

                $message = (new Swift_Message('BIENVENUE'))
                    ->setFrom('rayen.baccouch@esprit.tn')
                    ->setTo($user->getEmail())
                    ->setBody(
                        " Bienvenue a echkili "
                    );

                //send mail
                $mailer->send($message);
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('register/index.html.twig', [
            'controller_name' => 'RegisterController',
            'form' => $form->createView()
        ]);
    }
}
