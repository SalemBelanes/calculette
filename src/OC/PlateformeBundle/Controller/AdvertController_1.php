<?php

namespace OC\PlateformeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AdvertController extends Controller {

    public function indexAction() {
        return $this->render('OCPlateformeBundle:Advert:index.html.twig', array('nom' => 'winzou'));
        //return new Response("Notre propre Hello World!!");
//           $content = $this->get('templating')->render('OCPlatformBundle:Advert:index.html.twig');   
//           return new Response($content);
    }

    public function viewAction($id, Request $request) {

        // On veut avoir l'URL de l'annonce d'id 5.

        $url = $this->get('router')->generate(
                'oc_platform_view', // 1er argument : le nom de la route
                array('id' => 5), // 2e argument : les valeurs des paramètres
                UrlGeneratorInterface::ABSOLUTE_URL
        );
        $tag = $request->query->get('tag');

        // $url vaut « /platform/advert/5 »
        // Méthode courte
        //$url = $this->generateUrl('oc_platform_home');
        return new Response("Affichage de l'annonce d'id : " . $url . "tag:" . $tag);
        // return new RedirectResponse($url);
        //return $this->redirect($url);
        //return $this->redirectToRoute('oc_platform_home');
        ///json
//        $response = new Response(json_encode(array('id' => $id)));
//        $response->headers->set('Content-Type', 'application/json');
//
//        return $response;
        //return new JsonResponse(array('id' => $id));
    }

    public function viewSlugAction($slug, $year, $format) {

        return new Response(
                "On pourrait afficher l'annonce correspondant au

            slug '" . $slug . "', créée en " . $year . " et au format " . $format . "."
        );
    }

}
