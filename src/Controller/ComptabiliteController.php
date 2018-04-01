<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Reglement;
use App\Form\ReglementType;
use App\Form\EditReglementType;

class ComptabiliteController extends Controller{
    
    public function menuComptabilite()
    {
        return $this->render('Comptabilite/menuComptabilite.html.twig');
    }
    
    public function menuReglements()
    {
        $repository = $this->getDoctrine()->getRepository(Reglement::class);
        $listReglements = $repository->findAll();
        
        return $this->render('Comptabilite/menuReglements.html.twig', array(
            'listReglements'=>$listReglements
        ));
    }
    
    public function ficheReglement($idReglement){
        
        $em = $this->getDoctrine()->getManager();
        $reglement = $em->getRepository(Reglement::class)->find($idReglement);
        
        return $this->render('Comptabilite/ficheReglement.html.twig', array(
            'reglement' => $reglement,
            'visite'   => $reglement->getVisite(),
            'patient'   => $reglement->getVisite()->getPatient(),
        )); 
    }
    
    public function editerReglement($idReglement, Request $request){
        
        $em = $this->getDoctrine()->getManager();
        $reglement = $em->getRepository(Reglement::class)->find($idReglement);
        
        if (null === $reglement) {
            throw new NotFoundHttpException("Le  réglement d'id ".$idReglement." n'existe pas.");
        }
        
        $form = $this->get('form.factory')->create(EditReglementType::class, $reglement);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
        {
            $em->flush();
            return $this->redirectToRoute('fiche_reglement', array('idReglement' => $reglement->getId()));
        }
        return $this->render('Comptabilite/editerReglement.html.twig', array(
            'reglement' => $reglement,
            'form'   => $form->createView(),
        )); 
    }
    
    public function supprimerReglement($idReglement, Request $request ){
        
        $em = $this->getDoctrine()->getManager();
        $reglement = $em->getRepository(Reglement::class)->find($idReglement);
        
        if (null === $reglement) {
            throw new NotFoundHttpException("Le  réglement d'id ".$idReglement." n'existe pas.");
        }
        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        $form = $this->get('form.factory')->create();
       
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          $em->remove($reglement);
          $em->flush();
            
          $request->getSession()->getFlashBag()->add('info', "Le réglement a bien été supprimé.");

          return $this->redirectToRoute('menu_reglements');
        }

        return $this->render('Comptabilite/supprimerReglement.html.twig', array(
           'reglement' => $reglement,
           'visite'   => $reglement->getVisite(),
           'patient'   => $reglement->getVisite()->getPatient(),
           'form'   => $form->createView(),
        ));
    }
    
    
    
    
}