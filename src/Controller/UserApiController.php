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



    #[Route('/addUser/new', name: 'add_user')]
    public function addUser(ManagerRegistry $doctrine,Request $request, NormalizerInterface $Normalizer): Response
    {
        $entityManager = $doctrine->getManager();
        $user=new User();
        $user->setEmail($request->request->get('email'));
        $user->setLastName($request->request->get('lastname'));
        $user->setAdresse($request->request->get('adresse'));

        
        $entityManager->persist($user);
        dd($entityManager);
        $entityManager->flush();

        $jsonContent = $Normalizer->normalize($user , 'json' , ['groups'=> 'users']);

        return new Response(json_encode($jsonContent));
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
