<?php

namespace EvenementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EvenementBundle\Form\EvenementType;
use EvenementBundle\Entity\Evenement;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class EvenementController extends Controller
{
    /**
     * Ajouter
     */
    public function ajouterAdminAction(Request $request)
    {
        $evenement = new Evenement;
        $form = $this->get('form.factory')->create(EvenementType::class, $evenement);

        /* Récéption du formulaire */
        if ($form->handleRequest($request)->isValid()){
            $evenement->uploadImage();

            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();

            $request->getSession()->getFlashBag()->add('succes', 'Evénement enregistré avec succès');
            return $this->redirect($this->generateUrl('admin_evenement_manager'));
        }

        return $this->render('EvenementBundle:Admin:ajouter.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * Gestion
     */
    public function managerAdminAction(Request $request)
    {
        /* Services */
        $rechercheService = $this->get('recherche.service');
        $recherches = $rechercheService->setRecherche('evenement_manager', array(
                'recherche',
            )
        );

        /* La liste des événements */
        $evenements = $this->getDoctrine()
                           ->getRepository('EvenementBundle:Evenement')
                           ->getAllEvenements($recherches['recherche'], null, true);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $evenements, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            50/*limit per page*/
        );

        return $this->render('EvenementBundle:Admin:manager.html.twig',array(
                'pagination' => $pagination,
                'recherches' => $recherches
            )
        );
    }

    /**
     * Publication
     */
    public function publierAdminAction(Request $request, Evenement $evenement){

        if($request->isXmlHttpRequest()){
            $state = $evenement->reverseState();
            $evenement->setIsActive($state);

            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();

            return new JsonResponse(array('state' => $state));
        }

    }

    /**
     * Supprimer
     */
    public function supprimerAdminAction(Request $request, Evenement $evenement)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($evenement);
        $em->flush();

        $request->getSession()->getFlashBag()->add('succes', 'Evenement supprimé avec succès');
        return $this->redirect($this->generateUrl('admin_evenement_manager'));
    }

    /**
     * Modifier
     */
    public function modifierAdminAction(Request $request, Evenement $evenement)
    {
        $form = $this->get('form.factory')->create(EvenementType::class, $evenement);

        /* Récéption du formulaire */
        if ($form->handleRequest($request)->isValid()){
            $evenement->uploadImage();

            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();

            $request->getSession()->getFlashBag()->add('succes', 'Evenement enregistré avec succès');
            return $this->redirect($this->generateUrl('admin_evenement_manager'));
        }

        /* BreadCrumb */
        $breadcrumb = array(
            'Accueil' => $this->generateUrl('admin_page_index'),
            'Gestion des événements' => $this->generateUrl('admin_evenement_manager'),
            'Modifier un événement' => ''
        );

        return $this->render('EvenementBundle:Admin:modifier.html.twig',
            array(
                'breadcrumb' => $breadcrumb,
                'evenement' => $evenement,
                'form' => $form->createView()
            )
        );

    }

    /**
     * Supprimer l'image
     */
    public function AdminSupprimerImageAction(Request $request, Evenement $evenement)
    {
        if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();
            $evenement->setImage(null);
            $em->flush();

            return new JsonResponse(array('state' => 'ok'));
        }
    }

    /**
     * Manager client
     */
    public function managerClientAction(Request $request)
    {

        /* Services */
        $rechercheService = $this->get('recherche.service');
        $recherches = $rechercheService->setRecherche('evenements', array(
                'categorie',
            )
        );

        /* La liste des événements */
        $evenements = $this->getDoctrine()
                           ->getRepository('EvenementBundle:Evenement')
                           ->getAllEvenements(null, $recherches['categorie'], false);

        /* L'événement mis en avant */
        $avant = $this->getDoctrine()
                      ->getRepository('EvenementBundle:Evenement')
                      ->getAvantEvenement();

        /* La liste des catégories */
        $categories = $this->getDoctrine()
                           ->getRepository('EvenementBundle:Categorie')
                           ->findBy([],['id' => 'DESC']);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $evenements, /* query NOT result */
            $request->query->getInt('page', 1) /*page number*/,
            16 /*limit per page*/
        );

        return $this->render('EvenementBundle:Client:manager.html.twig', array(
                'pagination' => $pagination,
                'categories' => $categories,
                'recherches' => $recherches,
                'avant' => $avant
            )
        );
    }

    /*
     * View
     */
    public function viewClientAction($id)
    {
        /* Evenement en cours */
        $evenement = $this->getDoctrine()
                          ->getRepository('EvenementBundle:Evenement')
                          ->getCurrentEvenement($id);

        if(is_null($evenement)) throw new NotFoundHttpException('Cette page n\'est pas disponible');

        /* BreadCrumb */
        $breadcrumb = array(
            'Les événements' => $this->generateUrl('client_evenement_manager'),
            $evenement->getTitre() => ''
        );

        return $this->render( 'EvenementBundle:Client:view.html.twig',array(
                'evenement' => $evenement,
                'breadcrumb' => $breadcrumb
            )
        );
    }

    /**
     * Block template liste
     */
    public function lastEvenementAction($limit)
    {

        $evenements = $this->getDoctrine()
                           ->getRepository('EvenementBundle:Evenement')
                           ->getAllEvenements(null, null, false, $limit);

        return $this->render( 'EvenementBundle:Include:liste.html.twig',array(
                'evenements' => $evenements
            )
        );

    }

    /**
     * Block template calendrier
     */
    public function calendrierEvenementAction(Request $request, $annee = null, $mois = null)
    {
        $agenda = $this->get('agenda.service');
        $moisfr = $agenda->getMois();
        $joursfr = $agenda->getJours();
        $evenementsR = array();

        /* La liste des événements pour le calendier */
        $evenements = $this->getDoctrine()
                           ->getRepository('EvenementBundle:Evenement')
                           ->getAllEvenementsCalendrier();

        foreach ($evenements as $evenement){
            $evenementsR[$evenement->getDebut()->format('Y-m-d')][$evenement->getId()] = '<span>'.$evenement->getDebut()->format('d/m/Y').'</span><a href="#">'.$evenement->getTitre().'</a>';
        }

        if($request->isXmlHttpRequest()){

            return new JsonResponse(array(
                    'date' => $moisfr[$mois -1].' '.$annee,
                    'contenu' => $this->render('EvenementBundle:Include:calendrier-ajax.html.twig', array(
                        'calendrier' => $agenda->getCalendrier($annee),
                        'evenements' => $evenementsR,
                        'annee' => $annee,
                        'mois' => $mois,
                    ))->getContent()
                )
            );

        }else{
            $annee = date('Y');
            $mois = date('n');

            return $this->render( 'EvenementBundle:Include:calendrier.html.twig',array(
                    'calendrier' => $agenda->getCalendrier($annee),
                    'evenements' => $evenementsR,
                    'joursfr' => $joursfr,
                    'moisfr' => $moisfr,
                    'annee' => $annee,
                    'mois' => $mois
                )
            );
        }
    }
}
