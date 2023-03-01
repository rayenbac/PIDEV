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



    #[Route('/addUser', name: 'add_user', methods: ['POST'])]
    public function addUser(Request $request, EntityManagerInterface $entityManager): Response
    {
    $userData = $request->request->all(); // assuming form data is sent as x-www-form-urlencoded
    $user = new User();
    $user->setUsername($userData['username']);
    $user->setEmail($userData['email']);
    $user->setGender($userData['gender']);
    $user->setFirstname($userData['Firstname']);
    $user->setLastname($userData['Lastname']);
    $user->setAdresse($userData['adresse']);
    // set other properties as needed

    $entityManager->persist($user);
    $entityManager->flush();

    return new Response('User added successfully', Response::HTTP_CREATED);
    }







}
