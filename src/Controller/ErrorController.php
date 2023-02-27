<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends AbstractController
{
  
    public function error404(): Response
    {
        return $this->render('error/error-404.html.twig');
    }

    public function error403(): Response
    {
        
        return $this->render('error/error-403.html.twig',array('controller' => null,));
    }
}

