<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace BI\CalculetteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


/**
 * Description of AdvertController
 *
 * @author PC-MSI
 */
class AdvertController extends Controller{

    public function indexAction() {
        $content = $this->get('templating')->render('BICalculetteBundle:Advert:index.html.twig',array('nom'=>'Salem'));
        return new Response($content);
    }
        public function byeAction() {
        $content = $this->get('templating')->render('BICalculetteBundle:Advert:bye.html.twig',array('nom'=>'Salem'));
        return new Response($content);
    }

}
