<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Evenements;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EvenementsRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\EvenementFormType;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;



class EvenementsController extends AbstractController
{
    #[Route('/evenements', name: 'app_evenements')]
    public function index(): Response
    {
        return $this->render('evenements/index.html.twig', [
            'controller_name' => 'EvenementsController',
        ]);
    }
    #[Route('/afficheE', name: 'afficheE')]
    public function afficheE(EvenementsRepository $repository): Response
                {
                $e=$this->getDoctrine()->getRepository(Evenements::Class);
     //utiliser la fonction findAll()
        $e=$repository->findAll();
      
   return $this->render('evenements/afficheE.html.twig', [
    'evenements' => $e,
                    ]);
     }
     #[Route('/afficheEAdmin', name: 'afficheEAdmin')]
    public function afficheEAdmin(EvenementsRepository $repository): Response
                {
                $e=$this->getDoctrine()->getRepository(Evenements::Class);
     //utiliser la fonction findAll()
        $e=$repository->findAll();
      
   return $this->render('evenements/afficheEAdmin.html.twig', [
    'evenements' => $e,
                    ]);
     }
     #[Route('/addE', name: 'addE')]
     public function addE(ManagerRegistry $doctrine,Request $request, SluggerInterface $slugger)
                    {$evenement= new Evenements();
     $form=$this->createForm(EvenementFormType::class,$evenement);
                        $form->handleRequest($request);
                        if($form->isSubmitted() && $form->isValid()){
                            $currenttime = new \DateTime();
                                $evenement->setCreatedAt($currenttime);
                                $evenement->setUpdatedAt($currenttime);
                           
                            $Image = $form->get('Image')->getData();

                            // this condition is needed because the 'brochure' field is not required
                            // so the PDF file must be processed only when a file is uploaded
                            if ($Image) {
                                $originalFilename = pathinfo($Image->getClientOriginalName(), PATHINFO_FILENAME);
                                // this is needed to safely include the file name as part of the URL
                                $safeFilename = $slugger->slug($originalFilename);
                                $newFilename = $safeFilename.'-'.uniqid().'.'.$Image->guessExtension();
                
                                // Move the file to the directory where brochures are stored
                                try {
                                    $Image->move(
                                        $this->getParameter('evenement_directory'),
                                        $newFilename
                                    );
                                } catch (FileException $e) {
                                    // ... handle exception if something happens during file upload
                                }
                
                                // updates the 'brochureFilename' property to store the PDF file name
                                // instead of its contents
                                $evenement->setImage($newFilename);
                            }
                            $em =$doctrine->getManager() ;
                            $em->persist($evenement);
                            $em->flush();
                            return $this->redirectToRoute("afficheEAdmin");}
                   return $this->renderForm("evenements/addE.html.twig",
                            array("f"=>$form));
                     }
    #[Route('/updateEvenement/{id}', name: 'updateEvenement')]
                    public function updateEvenement(EvenementsRepository $repository,
                    $id,ManagerRegistry $doctrine,Request $request,SluggerInterface $slugger)
                    
                        {
                        $evenement= $repository->find($id);
                       
                        $form=$this->createForm(EvenementFormType::class,$evenement);
                        $form->handleRequest($request);
                        if($form->isSubmitted()  && $form->isValid()){
                            $currenttime = new \DateTime();
                            $evenement->setUpdatedAt($currenttime);
                            $Image = $form->get('Image')->getData();

                            // this condition is needed because the 'brochure' field is not required
                            // so the PDF file must be processed only when a file is uploaded
                            if ($Image) {
                                $originalFilename = pathinfo($Image->getClientOriginalName(), PATHINFO_FILENAME);
                                // this is needed to safely include the file name as part of the URL
                                $safeFilename = $slugger->slug($originalFilename);
                                $newFilename = $safeFilename.'-'.uniqid().'.'.$Image->guessExtension();
                
                                // Move the file to the directory where brochures are stored
                                try {
                                    $Image->move(
                                        $this->getParameter('evenement_directory'),
                                        $newFilename
                                    );
                                } catch (FileException $e) {
                                    // ... handle exception if something happens during file upload
                                }
                
                                // updates the 'brochureFilename' property to store the PDF file name
                                // instead of its contents
                                $evenement->setImage($newFilename);
                            }
                            

                            
                            
                            
                            $em =$doctrine->getManager();
                            $em->flush();
                            return $this->redirectToRoute("afficheEAdmin"); }
                        return $this->renderForm("evenements/updateE.html.twig",
                            array("f"=>$form));
                    }  
       #[Route('/suppEvenement/{id}', name: 'suppEvenement')]
           public function suppEvenement($id,EvenementsRepository $r,
           ManagerRegistry $doctrine): Response
           {
           $evenement=$r->find($id);
           
            $em =$doctrine->getManager();
            $em->remove($evenement);
            $em->flush();
 return $this->redirectToRoute('afficheEAdmin',);}              
      


//Code Api : 


#[Route('/ApiafficheE', name: 'ApiafficheE')]
public function ApiafficheE(EvenementsRepository $repository, NormalizerInterface $normalizer)
{
    $evenements = $repository->findAll();
    $evenementsNormalized = $normalizer->normalize($evenements, 'json', ['groups' => "e"]);
    $json = json_encode($evenementsNormalized, JSON_PRETTY_PRINT);

    return new Response($json, 200, ['Content-Type' => 'application/json']);
}

 

#[Route('/ApiaddE', name: 'ApiaddE')]
public function ApiaddE(ManagerRegistry $doctrine,Request $request, SluggerInterface $slugger , NormalizerInterface $Normalizer)
               {$evenement= new Evenements();
                $form = $this->createForm(EvenementFormType::class, $evenement);
                $currenttime = new \DateTime();
                $evenement->setCreatedAt($currenttime);
                $evenement->setUpdatedAt($currenttime);
                $evenement->setNomEvenement($request->get('NomEvenement'));
                $evenement->setDescriptionEvenement($request->get('DescriptionEvenement'));
                $evenement->setLieuEvenement($request->get('LieuEvenement'));
                $evenement->setDateEvenement($request->get('DateEvenement'));
                $evenement->setHeure($request->get('Heure'));
                $evenement->setNbrDePlaces($request->get('NbrDePlaces'));
                $evenement->setType($request->get('type'));
                   
                      
                       $Image = $form->get('Image')->getData();
                       if ($Image) {
                           $originalFilename = pathinfo($Image->getClientOriginalName(), PATHINFO_FILENAME);
                           // this is needed to safely include the file name as part of the URL
                           $safeFilename = $slugger->slug($originalFilename);
                           $newFilename = $safeFilename.'-'.uniqid().'.'.$Image->guessExtension();
           
                           // Move the file to the directory where brochures are stored
                           try {
                               $Image->move(
                                   $this->getParameter('evenement_directory'),
                                   $newFilename
                               );
                           } catch (FileException $e) {
                               // ... handle exception if something happens during file upload
                           }

                           $evenement->setImage($newFilename);
                       }
                       $em =$doctrine->getManager() ;
                       $em->persist($evenement);
                       $em->flush();
                       $jsonContent = $Normalizer->normalize($evenement, 'json', ['groups' => 'e']);
                       return new Response(json_encode($jsonContent));
                       
                       
                }

            

#[Route('/ApiupdateEvenement/{id}', name: 'ApiUpdateE')]
public function ApiUpdateE(ManagerRegistry $doctrine,Request $request, SluggerInterface $slugger , NormalizerInterface $Normalizer, $id , EvenementsRepository $repository)
               {$evenement = $EvenementsRepository->find($id);
                $form = $this->createForm(EvenementFormType::class, $evenement);
                $currenttime = new \DateTime();
                $evenement->setCreatedAt($currenttime);
                $evenement->setUpdatedAt($currenttime);
                $evenement->setNomEvenement($request->get('NomEvenement'));
                $evenement->setDescriptionEvenement($request->get('DescriptionEvenement'));
                $evenement->setLieuEvenement($request->get('LieuEvenement'));
                $evenement->setDateEvenement($request->get('DateEvenement'));
                $evenement->setHeure($request->get('Heure'));
                $evenement->setNbrDePlaces($request->get('NbrDePlaces'));
                $evenement->setType($request->get('type'));
                   
                      
                       $Image = $form->get('Image')->getData();
                       if ($Image) {
                           $originalFilename = pathinfo($Image->getClientOriginalName(), PATHINFO_FILENAME);
                           // this is needed to safely include the file name as part of the URL
                           $safeFilename = $slugger->slug($originalFilename);
                           $newFilename = $safeFilename.'-'.uniqid().'.'.$Image->guessExtension();
           
                           // Move the file to the directory where brochures are stored
                           try {
                               $Image->move(
                                   $this->getParameter('evenement_directory'),
                                   $newFilename
                               );
                           } catch (FileException $e) {
                               // ... handle exception if something happens during file upload
                           }

                           $evenement->setImage($newFilename);
                       }
                       $em =$doctrine->getManager() ;
                       $em->persist($evenement);
                       $em->flush();
                       $jsonContent = $Normalizer->normalize($evenement, 'json', ['groups' => 'e']);
                       return new Response( "Event updated !"   .json_encode($jsonContent));
                       
                       
                }
                #[Route('/ApisuppEvenement/{id}', name: 'ApisuppEvenement')]
public function ApisuppEvenement(NormalizerInterface $normalizer, ManagerRegistry $doctrine, EvenementsRepository $EvenementsRepository, $id)
{
    $evenement = $EvenementsRepository->find($id);
    $em = $doctrine->getManager();
    $em->remove($evenement);
    $em->flush();
    $json = $normalizer->normalize($evenement, 'json', ['groups' => 'e']);
    $response = new Response("Evenement supprimé avec succès : " . json_encode($json));
    $response->headers->set('Content-Type', 'application/json');
    return $response;
}

}









