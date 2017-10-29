<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace BI\CalculetteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of ProjetController
 *
 * @author PC-MSI
 */
class ProjetController extends Controller {

    public function indexAction($id) {
        $content = $this->get('templating')->render('BICalculetteBundle:Projet:index.html.twig', array('nom' => 'Salem', 'note' => $id));
        return new Response($content);
    }

    public function addAction($id, Request $request) {
        // $content = $this->get('templating')->render('BICalculetteBundle:Projet:add.html.twig',array('id'=>$id,'nom'=>'Salem'));
        //return new Response($content);
        $tag = $request->query->get('tag');
        return new Response(
                "Affichage de l'annonce d'id : " . $id . ", avec le tag : " . $tag
        );
    }

}
