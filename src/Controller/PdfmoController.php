<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RendezVousRepository;
use App\Entity\RendezVous;


use TCPDF;


class PdfController extends AbstractController
{
    #[Route('/pdfmoez/{id}', name: 'pdf')]

    public function pdfAction($id)
    {

        $rendezVous = $this->getDoctrine()->getRepository(RendezVous::class)->find($id); // Remplacez 1 par l'ID de votre rendez-vous

        // Récupérez les données à inclure dans le PDF
        $html = $this->renderView('pdf/pdfrendezv.html.twig', [
            'rendez_vou' => $rendezVous,
        ]);    
        // Créez un nouvel objet TCPDF
        $pdf = new TCPDF();
    
        // Ajoutez une nouvelle page au PDF
        $pdf->AddPage();
    
        // Écrivez les données dans le PDF
        $pdf->writeHTML($html);
    
        // Générez le contenu du PDF
        $content = $pdf->Output('', 'S');
    
        // Renvoyez le contenu du PDF au navigateur
        return new Response($content, 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="RendezVous.pdf"'
        ));
    }
}
