<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\RegisterType;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserApiController extends AbstractController
{
   

    #[Route('/allUsers', name: 'list')]
    public function getUsers(NormalizerInterface $normalizer,ManagerRegistry $doctrine,SerializerInterface $serializer):Response
    {
        $users=$doctrine->getRepository(User::class)->findAll();
        $json= $serializer->serialize($users,'json',['groups'=>"users"]);
        
       return new Response($json);
    }

    #[Route('/user/{id}', name: 'user_show')]
    public function findUser(ManagerRegistry $doctrine, SerializerInterface $serializer, int $id): Response
    {
    $user = $doctrine->getRepository(User::class)->find($id);

    if (!$user) {
        return new Response(null, Response::HTTP_NOT_FOUND);
    }

    $json = $serializer->serialize($user, 'json', ['groups' => 'users']);

    return new Response($json, Response::HTTP_OK, [
        'Content-Type' => 'application/json'
    ]);
    }



    #[Route('/signup', name: 'signup' , methods:['POST'])]
    public function addUser(ManagerRegistry $doctrine,Request $request, NormalizerInterface $Normalizer,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $entityManager = $doctrine->getManager();
        $email=$request->get('email');
        $password =$request->get('password');
        $roles= $request->get('roles');
        $date = $request->get( ('birthDate'));
        $user=new User();
        $user->setEmail($email);
        $user->setRoles(array($roles));
        $user->setPassword($passwordEncoder->encodePassword($user,$password));
        $user->setLastName("ahmed");
        $user->setFirstName("mohamed");
        $user->setAdresse("paris");
        $user->setBirthDate(new \DateTimeImmutable($date));
        $user->setGender("H");
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setStatus(0);
        $entityManager->persist($user);
        $entityManager->flush();

        $jsonContent = $Normalizer->normalize($user , 'json' , ['groups'=> 'users']);

        
        return new Response(json_encode($jsonContent));
    
    }

    #[Route('/signin', name: 'signin' )]
    public function signin(ManagerRegistry $doctrine,Request $request, NormalizerInterface $Normalizer,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $email=$request->get('email');
        $password =$request->get('password');


        $entityManager = $doctrine->getManager();
        $user=$doctrine->getRepository(User::class)->findOneBy(['email' => $email]);;

        if ($user) {

            if (password_verify($password,$user->getPassword())) {

                $serializer = new Serializer([ new ObjectNormalizer()]);
                $formatted = $serializer->normalize($user);
                return new JsonResponse($formatted);
            }
            else {

                return new Response("password not found");
            }
        }
        else {

            return new Response("user not found");
        }



    }


    


    #[Route('/supprimer/{id}', name: 'user_delete')]
    public function deleteUser(ManagerRegistry $doctrine,SerializerInterface $serializer, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
        
        if (!$user) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        
        $entityManager->remove($user);
        $entityManager->flush();

        $json = $serializer->serialize($user, 'json', ['groups' => 'users']);
        return new Response('User has been deleted successfully', Response::HTTP_OK);
    }





}
