<?php

namespace App\Controller;


use Dompdf\Dompdf;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Mood;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\MoodRepository;
use App\Form\FormMoodType;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;



use Knp\Component\Pager\PaginatorInterface;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;

class MoodController extends AbstractController
{
    #[Route('/mood', name: 'app_mood')]
    public function index(): Response
    {
        return $this->render('mood/index.html.twig', [
            'controller_name' => 'MoodController',
        ]);
    }

    #[Route('/afficheM', name: 'afficheM')]
    public function afficheM(): Response
                {
     //récupérer le repository
     $c=$this->getDoctrine()->getRepository(Mood::Class)->findAll();
     //utiliser la fonction findAll()
     //$c=$r->findAll();
   return $this->render('mood/index.html.twig', [
    'm' => $c
                    ]);
     }


     



     #[Route('/add/mood', name: 'addMood')]
     public function addMood(ManagerRegistry $doctrine,Request $request)
                    {$mood= new Mood();
                     
                     $form=$this->createForm(FormMoodType::class,$mood);
   
                        $form->handleRequest($request);
                        if($form->isSubmitted() && $form->isValid()){
                         
                        
                        
                            $em =$doctrine->getManager() ;
                            $em->persist($mood);
                            $em->flush();
                            return $this->redirectToRoute("afficheM");}
                   return $this->renderForm("mood/add.html.twig",
                            array("f"=>$form));
                     }

    
    #[Route('/editmood/{id}', name: 'editmood')]
    public function editmood (MoodRepository $repository,
    $id,ManagerRegistry $doctrine,Request $request)
    { //récupérer le classroom à modifier
        $mood= $repository->find($id);
        $form=$this->createForm(FormMoodType::class,$mood);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em =$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute("afficheM"); }
        return $this->renderForm("mood/edit.html.twig",
            array("f"=>$form));
    }
    

    #[Route('/deletemood/{id}', name:'deletemood')]
    public function deletemood($id, ManagerRegistry $doctrine , MoodRepository $r): Response
    {
        $mood=$r->find($id);
        $em=$doctrine->getManager();
        $em->remove($mood);
        $em->flush();
        return $this->redirectToRoute('afficheM'); 


    }
    #[Route('/afficheAdminMood', name: 'afficheAdminMood')]
     public function afficheAdminMood(): Response
                 {
      //récupérer le repository
      $c=$this->getDoctrine()->getRepository(Mood::Class)->findAll();
      //utiliser la fonction findAll()
      //$c=$r->findAll();
    return $this->render("mood/afficheA.html.twig", [
     'm' => $c
                     ]);
      }



      #[Route('/afficheApi', name: 'afficheApi')]
public function afficheApi(MoodRepository $repo, NormalizerInterface $normalizer)
{
     $moods = $repo->findAll();
     $moodsNormalises = $normalizer->normalize($moods, 'json', ['groups' => "moods"]);

     $json = json_encode($moodsNormalises);

     return new Response($json);
}
 


#[Route('/addMoodJSON/new', name: 'addMoodJSON')]
public function addMoodJSON(Request $req, NormalizerInterface $Normalizer)
{
     $em = $this->getDoctrine()->getManager();
     $mood = new Mood();
     $mood->setMoodId($req->get('MoodId'));
     $mood->setUserId($req->get('UserId'));
     $mood->setMood($req->get('Mood'));
     $mood->setDescription($req->get('Description'));
     $em->persist($mood);
     $em->flush();

     $jsonContent = $Normalizer->normalize($mood, 'json', ['groups' => 'moods']);
     return new Response(json_encode($jsonContent));
}


#[Route('/updateMoodJSON/{id}', name: 'updateMoodJSON')]
public function updateMoodJSON(Request $req, $id, NormalizerInterface $Normalizer)
{
    $em = $this->getDoctrine()->getManager();
     $mood = $em->getRepository(Mood::class)->find($id);
     $mood->setMoodId($req->get('MoodId'));
     $mood->setUserId($req->get('UserId'));
     $mood->setMood($req->get('Mood'));
     $mood->setDescription($req->get('Description'));

     $em->flush();

     $jsonContent = $Normalizer->normalize($mood, 'json', ['groups' => 'moods']);
     return new Response("Mood updated successfully" . json_encode($jsonContent));
}


#[Route('/deleteMoodJSON/{id}', name: 'deleteMoodJSON')]
public function deleteMoodJSON(Request $req, $id, NormalizerInterface $Normalizer)
{
     $em = $this->getManager();
     $mood = $em->getRepository(Mood::class)->find($id);
     $em->remove($mood);
     $em->flush();

     $jsonContent = $Normalizer->normalize($mood, 'json', ['groups' => 'moods']);
     return new Response("Mood deleted successfully" . json_encode($jsonContent));
}



    #[Route('/pdf', name: 'pdf', methods: ['GET'])]
    public function pdf(MoodRepository $MoodRepository): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new OptionsResolver();
        $pdfOptions->setDefaults([
            'defaultFont' => 'Arial',
        ]);
    
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
    
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('mood/pdf.html.twig', [
            'moods' => $MoodRepository->findAll(),
        ]);
    
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
    
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');
    
        // Render the HTML as PDF
        $dompdf->render();
    
        // Output the generated PDF to Browser (inline view)
        $output = $dompdf->output();
        $response = new Response($output);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'inline; filename="mypdf.pdf"');
        return $response;
    }

}



