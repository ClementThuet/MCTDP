<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Produit;
use App\Entity\Materiel;
use App\Form\MaterielType;
use App\Form\EditMaterielType;
use App\Form\ProduitType;
use App\Form\EditProduitType;

class MaterielController extends Controller{

    public function menuMateriel()
    {
        return $this->render('Materiel/menuMateriel.html.twig');
    }
    
    public function menuProduits()
    {
        $repository = $this->getDoctrine()->getRepository(Produit::class);
        $listProduits = $repository->findAll();
        
        return $this->render('Materiel/menuProduits.html.twig', array(
            'listProduits'=>$listProduits
        ));
    }
    
    //Non nécessaire imo
    public function ficheProduit($idProduit){
        
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(Produit::class)->find($idProduit);
        
        return $this->render('Materiel/ficheProduit.html.twig', array(
            'produit' => $produit,
        )); 
    }
    
    public function ajouterProduit(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
       
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
           
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            
            return $this->redirectToRoute('menu_produits');
        }
        
        return $this->render('Materiel/ajouterProduit.html.twig', array(
            'form' => $form->createView()
                 ));
     }
     
    public function editerProduit($idProduit, Request $request){
        
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(Produit::class)->find($idProduit);
        
        if (null === $produit) {
            throw new NotFoundHttpException("Le produit d'id ".$idProduit." n'existe pas.");
        }
        
        $form = $this->get('form.factory')->create(EditProduitType::class, $produit);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
        {
            $em->flush();
            return $this->redirectToRoute('menu_produits', array('idProduit' => $produit->getId()));
        }
        return $this->render('Materiel/editerProduit.html.twig', array(
            'produit' => $produit,
            'form'   => $form->createView(),
        )); 
    }
    
    public function supprimerProduit($idProduit, Request $request ){
        
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(Produit::class)->find($idProduit);
        
        if (null === $produit) {
            throw new NotFoundHttpException("Le  réglement d'id ".$idProduit." n'existe pas.");
        }
        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        $form = $this->get('form.factory')->create();
       
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          $em->remove($produit);
          $em->flush();
            
          $request->getSession()->getFlashBag()->add('info', "Le produit a bien été supprimé.");

          return $this->redirectToRoute('menu_produits');
        }

        return $this->render('Materiel/supprimerProduit.html.twig', array(
           'produit' => $produit,
           'form'   => $form->createView(),
        ));
    }
    #Materiel
     public function menuMateriels()
    {
        $repository = $this->getDoctrine()->getRepository(Materiel::class);
        $listMateriels = $repository->findAll();
        
        return $this->render('Materiel/menuMateriels.html.twig', array(
            'listMateriels'=>$listMateriels
        ));
    }
    
    //Non nécessaire imo
    public function ficheMateriel($idMateriel){
        
        $em = $this->getDoctrine()->getManager();
        $materiel = $em->getRepository(Produit::class)->find($idMateriel);
        
        return $this->render('Materiel/ficheMateriel.html.twig', array(
            'materiel' => $materiel,
        )); 
    }
    
    public function ajouterMateriel(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $materiel = new Materiel();
        $form = $this->createForm(MaterielType::class, $materiel);
       
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
           
            $em = $this->getDoctrine()->getManager();
            $em->persist($materiel);
            $em->flush();
            
            return $this->redirectToRoute('menu_materiels');
        }
        
        return $this->render('Materiel/ajouterMateriel.html.twig', array(
            'form' => $form->createView()
                 ));
     }
     
    public function editerMateriel($idMateriel, Request $request){
        
        $em = $this->getDoctrine()->getManager();
        $materiel = $em->getRepository(Materiel::class)->find($idMateriel);
        
        if (null === $materiel) {
            throw new NotFoundHttpException("Le materiel d'id ".$idMateriel." n'existe pas.");
        }
        
        $form = $this->get('form.factory')->create(EditMaterielType::class, $materiel);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
        {
            $em->flush();
            return $this->redirectToRoute('menu_materiels');
        }
        return $this->render('Materiel/editerMateriel.html.twig', array(
            'materiel' => $materiel,
            'form'   => $form->createView(),
        )); 
    }
    
    public function supprimerMateriel($idMateriel, Request $request ){
        
        $em = $this->getDoctrine()->getManager();
        $materiel = $em->getRepository(Materiel::class)->find($idMateriel);
        
        if (null === $materiel) {
            throw new NotFoundHttpException("Le  réglement d'id ".$idMateriel." n'existe pas.");
        }
        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        $form = $this->get('form.factory')->create();
       
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          $em->remove($materiel);
          $em->flush();
            
          $request->getSession()->getFlashBag()->add('info', "Le produit a bien été supprimé.");

          return $this->redirectToRoute('menu_materiels');
        }

        return $this->render('Materiel/supprimerMateriel.html.twig', array(
           'materiel' => $materiel,
           'form'   => $form->createView(),
        ));
    }
    
    
    
    
}