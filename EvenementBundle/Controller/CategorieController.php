<?php

namespace EvenementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EvenementBundle\Form\CategorieType;
use EvenementBundle\Entity\Categorie;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategorieController extends Controller
{

    /**
     * Ajouter
     */
    public function ajouterAdminAction(Request $request)
    {
        $categorie = new Categorie;
        $form = $this->get('form.factory')->create(CategorieType::class, $categorie);

        /* Récéption du formulaire */
        if ($form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            $request->getSession()->getFlashBag()->add('succes', 'Catégorie enregistrée avec succès');
            return $this->redirect($this->generateUrl('admin_evenementcategorie_manager'));
        }

        return $this->render('EvenementBundle:Admin/Categorie:ajouter.html.twig',
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
        $recherches = $rechercheService->setRecherche('evenementcategorie_manager', array(
                'langue'
            )
        );

        $categories = $this->getDoctrine()
                           ->getRepository('EvenementBundle:Categorie')
                           ->getAllCategorie($recherches['langue']);

        /* La liste des langues */
        $langues = $this->getDoctrine()->getRepository('GlobalBundle:Langue')->findAll();

        return $this->render('EvenementBundle:Admin/Categorie:manager.html.twig',array(
                'langues' => $langues,
                'categories' => $categories,
                'recherches' => $recherches
            )
        );
    }

    /**
     * Supprimer
     */
    public function supprimerAdminAction(Request $request, Categorie $categorie)
    {
        if(count($categorie->getEvenements()) != 0)  throw new NotFoundHttpException('Cette page n\'est pas disponible');

        $em = $this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();

        $request->getSession()->getFlashBag()->add('succes', 'Catégorie supprimée avec succès');
        return $this->redirect($this->generateUrl('admin_evenementcategorie_manager'));
    }

    /**
     * Modifier
     */
    public function modifierAdminAction(Request $request, Categorie $categorie)
    {
        $form = $this->get('form.factory')->create(CategorieType::class, $categorie);

        /* Récéption du formulaire */
        if ($form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            $request->getSession()->getFlashBag()->add('succes', 'Catégorie enregistrée avec succès');
            return $this->redirect($this->generateUrl('admin_evenementcategorie_manager'));
        }

        /* BreadCrumb */
        $breadcrumb = array(
            'Accueil' => $this->generateUrl('admin_page_index'),
            'Gestion des catégories' => $this->generateUrl('admin_evenementcategorie_manager'),
            'Modifier une catégorie' => ''
        );

        return $this->render('EvenementBundle:Admin/Categorie:modifier.html.twig',
            array(
                'breadcrumb' => $breadcrumb,
                'categorie' => $categorie,
                'form' => $form->createView()
            )
        );

    }

}
