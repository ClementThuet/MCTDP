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
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap4View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Length;


class MaterielController extends Controller{
    
     
    public function menuMateriel()
    {
        
        return $this->render('Materiel/menuMateriel.html.twig');
    }
    
    public function menuProduits($page,Request $request)
    {
        $page = $request->query->get('page', $page);
        
        $qb = $this->getDoctrine()
            ->getRepository(Produit::class)
            ->findAllQueryBuilder();
        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(10);
        $pagerfanta->setCurrentPage($page);
        $pagerfanta->haveToPaginate(); // whether the number of results is higher than the max per page

        $view = new TwitterBootstrap4View();
        $options = array('proximity' => 3,
            'prev_message'=>'← Précédent',
            'next_message'=> 'Suivant →',
            'css_container_class' =>'pagination');

        $routeGenerator = function($page) {
            return 'page-'.$page;
        };

        $html = $view->render($pagerfanta, $routeGenerator, $options);
        $produits = [];
        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $produits[] = $result;
        }
        
        return $this->render('Materiel/menuProduits.html.twig', array(
            'listProduits'=>$produits,
            'html' => $html
        ));
    }
    
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
            return $this->redirectToRoute('menu_produits');
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
    public function menuMateriels($page,Request $request)
    {
        $page = $request->query->get('page', $page);
        
        $qb = $this->getDoctrine()
            ->getRepository(Materiel::class)
            ->findAllQueryBuilder();
        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(6);
        $pagerfanta->setCurrentPage($page);
        $pagerfanta->haveToPaginate(); // whether the number of results is higher than the max per page

        $view = new TwitterBootstrap4View();
        $options = array('proximity' => 3,
            'prev_message'=>'← Précédent',
            'next_message'=> 'Suivant →',
            'css_container_class' =>'pagination');

        $routeGenerator = function($page) {
            return 'page-'.$page;
        };

        $html = $view->render($pagerfanta, $routeGenerator, $options);
        $materiels = [];
        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $materiels[] = $result;
        }
        //\Doctrine\Common\Util\Debug::dump($pagerfanta);
        return $this->render('Materiel/menuMateriels.html.twig',array(         
            'listMateriels' => $materiels,
            'html' => $html
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
            
            return $this->redirectToRoute('menu_materiels',array('page'=>1));
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
            return $this->redirectToRoute('menu_materiels',array('page'=>1));
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

          return $this->redirectToRoute('menu_materiels',array('page'=>1));
        }

        return $this->render('Materiel/supprimerMateriel.html.twig', array(
           'materiel' => $materiel,
           'form'   => $form->createView(),
        ));
    }
    
    public function rechercherMateriel($Entite, $champ, $valeur,Request $request){
    
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('m ')
        ->from('App\Entity\\'.$Entite.'', 'm')
        ->where('m.'.$champ.' LIKE :valeur ')
        ->orderBy('m.'.$champ.'', 'ASC')
        ->setParameter('valeur', '%'.$valeur.'%');
        
        $query = $qb->getQuery();
        $listMateriels = $query->getResult();
        
        return $this->render('Materiel/menuMateriels.html.twig', array(
            'listMateriels'=>$listMateriels,
            'rechercheEffectuee'=>1,
        ));
    }
    
    public function rechercherProduit($Entite, $champ, $valeur){
        if($champ == "categorie")
        {
            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder();
            $qb 
            ->select('cat')
            ->from('App\Entity\Categorie', 'cat')        
            ->where('cat.nom LIKE :valeur')
            ->setParameter('valeur', '%'.$valeur.'%');
            $query = $qb->getQuery();
            $listCats = $query->getResult();
            
            foreach($listCats as $categorie){
                $ids=$categorie->getId();
            }
            if (isset($ids))
            {
                $em2 = $this->getDoctrine()->getManager();
                $qb2 = $em2->createQueryBuilder();
                $qb2 
                ->select('p')
                ->from('App\Entity\Produit', 'p')   
                ->innerJoin('p.categorie', 'cat', 'WITH', 'cat.id = :valeur')
                ->setParameter('valeur', ''.$ids.'');
                $query2 = $qb2 ->getQuery();
                $listProduits = $query2->getResult();
            }
            else{
                $listProduits='';
            }
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder();
            $qb->select('m ')
            ->from('App\Entity\\'.$Entite.'', 'm')
            ->where('m.'.$champ.' LIKE :valeur ')
            ->orderBy('m.'.$champ.'', 'ASC')
            ->setParameter('valeur', '%'.$valeur.'%');
             $query = $qb->getQuery();
             $listProduits = $query->getResult();
        }
        //\Doctrine\Common\Util\Debug::dump($ids);
        return $this->render('Materiel/menuProduits.html.twig', array(
            'listProduits'=>$listProduits,
            'rechercheEffectuee'=>1,
        ));
    }
    
    public function filtrerMateriel(Request $request)
    {
        if($request->get('inputSsStock')=="false")
        {
            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder();
            $qb->select('m ')
            ->from('App\Entity\Materiel', 'm')
            ->where('m.qteStock != 0 ')
            ->orderBy('m.nom', 'ASC');
        }
        if($request->get('inputSsStock') == "true")
        {
            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder();
            $qb->select('m ')
            ->from('App\Entity\Materiel', 'm')
            ->where('m.qteStock = 0 ')
            ->orderBy('m.nom', 'ASC');
        }
        
        $query = $qb->getQuery();
        $listMateriels = $query->getResult();
        $tabMateriel=[];
        for($i=0;$i<count($listMateriels);$i++)
        {
           $tabMateriel[$i]=' <tr class="parent" onclick="document.location = \'/Materiel/editer-materiel/'.$listMateriels[$i]->getId().'\';" >
                                <td>'.$listMateriels[$i]->getNom().'</td>
                                <td>'.$listMateriels[$i]->getCategorie()->getNom().' </td>
                                <td>'.$listMateriels[$i]->getDescription().' </td>
                                <td>'.$listMateriels[$i]->getNumLot().'</td>
                                <td>'.$listMateriels[$i]->getQteStock().'</td>
                                <td><a href="/Materiel/editer-materiel/'.$listMateriels[$i]->getId().'"})}}><i class="fas fa-edit glyphMenu" ></i></a></td>
                                <td><a href="/Materiel/supprimer-materiel/'.$listMateriels[$i]->getId().'"})}}>  <i class="fas fa-trash-alt glyphMenu"  ></i></a></td>    
                            </tr>';
        }
        
        $response = new JsonResponse;
        $response->setContent(json_encode(array(
        'listMateriels' => $tabMateriel)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    
}