<?php

namespace OC\PlateformeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OC\PlateformeBundle\Entity\Advert;
use OC\PlateformeBundle\Entity\Image;
use OC\PlateformeBundle\Entity\Application;
use OC\PlateformeBundle\Entity\AdvertSkill;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdvertController extends Controller {

    public function indexAction($page) {
        // On ne sait pas combien de pages il y a
        // Mais on sait qu'une page doit être supérieure ou égale à 1
        if ($page < 1) {
            // On déclenche une exception NotFoundHttpException, cela va afficher
            // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
            throw new NotFoundHttpException('Page "' . $page . '" inexistante.');
        }

        // Ici, on récupérera la liste des annonces, puis on la passera au template
        // Mais pour l'instant, on ne fait qu'appeler le template
        //return $this->render('OCPlateformeBundle:Advert:index.html.twig');
        return $this->render('OCPlateformeBundle:Advert:index.html.twig', array(
                    'listAdverts' => array(array(
                            'title' => 'Recherche développpeur Symfony',
                            'id' => 1,
                            'author' => 'Alexandre',
                            'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
                            'date' => new \Datetime()),
                        array(
                            'title' => 'Mission de webmaster',
                            'id' => 2,
                            'author' => 'Hugo',
                            'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
                            'date' => new \Datetime()),
                        array(
                            'title' => 'Offre de stage webdesigner',
                            'id' => 3,
                            'author' => 'Mathieu',
                            'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
                            'date' => new \Datetime())
                    )
        ));
    }

    public function viewAction($id) {
//        $advert = array(
//            'title' => 'Recherche développpeur Symfony2',
//            'id' => $id,
//            'author' => 'Alexandre',
//            'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
//            'date' => new \Datetime()
//        );
//        
//        // Depuis un contrôleur
//$advert = $this->getDoctrine()
//  ->getManager()
//  ->find('OCPlateformeBundle:Advert', $id);
        // On récupère le repository
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('OCPlateformeBundle:Advert');
        // On récupère l'entité correspondante à l'id $id
        $advert = $repository->find($id);
        // $advert est donc une instance de OC\PlateformeBundle\Entity\Advert
        // ou null si l'id $id  n'existe pas, d'où ce if :

        if (null === $advert) {

            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }
// je vais récupérer la liste des candidatures des applications faites sur une annonce

        $repositoryApplication = $em->getRepository('OCPlateformeBundle:Application');
        $listApplications = $repositoryApplication->findBy(array('advert' => $advert));

// Je vais recupérer la liste des Skills
        $listAdvertSkill = $em->getRepository('OCPlateformeBundle:AdvertSkill')->findBy(array('advert' => $advert));
        return $this->render('OCPlateformeBundle:Advert:view.html.twig', array(
                    'advert' => $advert,
                    'listApplications' => $listApplications,
                    'listAdvertSkill' => $listAdvertSkill
        ));
    }

    public function addAction(Request $request) {
        // On crée un objet Advert
        $advert = new Advert();

        // On crée le FormBuilder grâce au service form factory
        // Création de l'entité Image
        $image = new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        $image->setAlt('Job de rêve');

        // On lie l'image à l'annonce
        $advert->setImage($image);
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $advert);
// Ici, on préremplit avec la date d'aujourd'hui, par exemple
// Cette date sera donc préaffichée dans le formulaire, cela facilite le travail de l'utilisateur
        $advert->setDate(new \Datetime());
        // On ajoute les champs de l'entité que l'on veut à notre formulaire
        $formBuilder
                ->add('date', DateType::class, array(
                    'widget' => 'single_text',
                    // do not render as type="date", to avoid HTML5 date pickers
                    'html5' => false,
                    // add a class that can be selected in JavaScript
                    'attr' => ['class' => 'js-datepicker'],
                ))
                ->add('title', TextType::class)
                ->add('content', TextareaType::class)
                ->add('author', TextType::class)
                ->add('published', CheckboxType::class, array('required' => true))
                ->add('save', SubmitType::class)
        ;
        // À partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();
        // Pour l'instant, pas de candidatures, catégories, etc., on les gérera plus tard
// on va tester sur l etype de requete

        if ($request->isMethod('Post')) {
            //on fait le lien requête <-> Formulaire
            // A partir de maintenant, la vocabulaire $advert contient les valeurs entrées dans le formulaire par le visiteur
            $form->handleRequest($request);
            //on vérifie que les valeurs entrées sont correctes
            // nous verrons la validation par détail
            if ($form->isValid()) {
                //on enregistre notre objet $advert dans la base de donnée, par exemple
                $em = $this->getDoctrine()->getManager();
                $em->persist($advert);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

                // On redirige vers la page de visualisation de l'annonce nouvellement créée
                return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
            }
        }




        // On passe la méthode createView() du formulaire à la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('OCPlateformeBundle:Advert:add.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function editAction($id, Request $request) {
        // Ici, on récupérera l'annonce correspondante à $id
        // Même mécanisme que pour l'ajout
        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

            return $this->redirectToRoute('oc_platform_view', array('id' => 5));
        }
//        $advert = array(
//            'title' => 'Recherche développpeur Symfony',
//            'id' => $id,
//            'author' => 'Alexandre',
//            'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
//            'date' => new \Datetime()
//        );
        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $advert = $em->getRepository('OCPlateformeBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }

        // La méthode findAll retourne toutes les catégories de la base de données
        $listCategories = $em->getRepository('OCPlateformeBundle:Category')->findAll();

        // On boucle sur les catégories pour les lier à l'annonce
        foreach ($listCategories as $category) {
            $advert->addCategory($category);
        }

        // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
        // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine
        // Étape 2 : On déclenche l'enregistrement
        $em->flush();


        return $this->render('OCPlateformeBundle:Advert:edit.html.twig', array(
                    'advert' => $advert,
        ));
    }

    public function deleteAction($id) {
        // Ici, on récupérera l'annonce correspondant à $id
        // Ici, on gérera la suppression de l'annonce en question
        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $advert = $em->getRepository('OCPlateformeBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }


        // On boucle sur les catégories pour les lier à l'annonce
        foreach ($advert->getCategories() as $category) {
            $advert->removeCategory($category);
        }
        // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
        // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine
        // On déclenche la modification

        $em->flush();
        return $this->render('OCPlateformeBundle:Advert:delete.html.twig');
    }

    public function menuAction($limit) {
        // On fixe en dur une liste ici, bien entendu par la suite
        // on la récupérera depuis la BDD !
        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche développeur Symfony'),
            array('id' => 5, 'title' => 'Mission de webmaster'),
            array('id' => 9, 'title' => 'Offre de stage webdesigner')
        );

        return $this->render('OCPlateformeBundle:Advert:menu.html.twig', array(
                    // Tout l'intérêt est ici : le contrôleur passe
                    // les variables nécessaires au template !
                    'listAdverts' => $listAdverts
        ));
    }

    public function editImageAction($advertId) {

        $em = $this->getDoctrine()->getManager();


        // On récupère l'annonce

        $advert = $em->getRepository('OCPlateformeBundle:Advert')->find($advertId);


        // On modifie l'URL de l'image par exemple

        $advert->getImage()->setUrl('test.png');


        // On n'a pas besoin de persister l'annonce ni l'image.
        // Rappelez-vous, ces entités sont automatiquement persistées car
        // on les a récupérées depuis Doctrine lui-même
        // On déclenche la modification

        $em->flush();


        return new Response('OK');
    }

}
