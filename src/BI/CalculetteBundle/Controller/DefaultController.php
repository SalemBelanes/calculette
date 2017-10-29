<?php

namespace BI\CalculetteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BICalculetteBundle:Default:index.html.twig');
    }
}
