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
}          


