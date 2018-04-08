<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Specialite;
use App\Form\SpecialiteType;
use App\Form\EditSpecialiteType;
use App\Entity\Fournisseur;
use App\Form\FournisseurType;
use App\Form\EditFournisseurType;
use App\Entity\Medecin;
use App\Form\MedecinType;
use App\Form\EditMedecinType;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Form\EditCategorieType;

class ParametresController extends Controller{

    public function menuParametres()
    {
        return $this->render('Parametres/menuParametres.html.twig');
    }
    
    public function menuSpecialites()
    {
        $em = $this->getDoctrine()->getManager();
        $listSpecialites = $em->getRepository(Specialite::class)->findAll();
        
         return $this->render('Parametres/menuSpecialites.html.twig'
                 , array('listSpecialites'=> $listSpecialites ));
    }
    public function ajouterSpecialite(Request $request){
    
        $specialite = new Specialite();
        $form = $this->createForm(SpecialiteType::class, $specialite);
       
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($specialite);
            $em->flush();
            
            //$repository = $this->getDoctrine()->getRepository(Medecin::class);
  
            return $this->redirectToRoute('menu_specialites');
        }
        return $this->render('Parametres/ajouterSpecialite.html.twig', array(
          'form' => $form->createView(),
        ));
    }
    
    public function editerSpecialite($idSpecialite, Request $request){
        
        $em = $this->getDoctrine()->getManager();
        $specialite = $em->getRepository(Specialite::class)->find($idSpecialite);
        if (null === $specialite) {
            throw new NotFoundHttpException("Le  coupon d'id ".$idSpecialite." n'existe pas.");
        }
        
        $form = $this->get('form.factory')->create(EditSpecialiteType::class, $specialite);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
        {
            $em->flush();
            return $this->redirectToRoute('menu_specialites');
        }
        return $this->render('Parametres/editerSpecialite.html.twig', array(
            'specialite' => $specialite,
            'form'   => $form->createView(),
        )); 
    }
    
    public function supprimerSpecialite($idSpecialite, Request $request ){
        
        $em = $this->getDoctrine()->getManager();
        $specialite = $em->getRepository(Specialite::class)->find($idSpecialite);
        
        if (null === $specialite) {
            throw new NotFoundHttpException("La spécialité d'id ".$idSpecialite." n'existe pas.");
        }
        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        $form = $this->get('form.factory')->create();
       
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          $em->remove($specialite);
          $em->flush();
            
          $request->getSession()->getFlashBag()->add('info', "La spécialité a bien été supprimée.");

          return $this->redirectToRoute('menu_specialites');
        }

        return $this->render('Parametres/supprimerSpecialite.html.twig', array(
          'specialite' => $specialite,
          'form'   => $form->createView(),
        ));
    }
    
    public function menuFournisseurs()
    {
        $em = $this->getDoctrine()->getManager();
        $listFournisseurs = $em->getRepository(Fournisseur::class)->findAll();
        
         return $this->render('Parametres/menuFournisseurs.html.twig'
                 , array('listFournisseurs'=> $listFournisseurs ));
    }
    
    public function ficheFournisseur($idFournisseur)
    {
        $em = $this->getDoctrine()->getManager();
        $fournisseur = $em->getRepository(Fournisseur::class)->find($idFournisseur);
        
        return $this->render('Parametres/ficheFournisseur.html.twig'
                 , array('fournisseur'=> $fournisseur ));
    }
    
    public function ajouterFournisseur(Request $request){
    
        $fournisseur = new Fournisseur();
        $form = $this->createForm(FournisseurType::class, $fournisseur);
       
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fournisseur);
            $em->flush();
            
            $repository = $this->getDoctrine()->getRepository(Fournisseur::class);
            
            
            return $this->redirectToRoute('menu_fournisseurs');
        }
        return $this->render('Parametres/ajouterFournisseur.html.twig', array(
          'form' => $form->createView(),
        ));
    }
    
    public function editerFournisseur($idFournisseur, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $fournisseur = $em->getRepository(Fournisseur::class)->find($idFournisseur);
        if (null === $fournisseur) {
            throw new NotFoundHttpException("Le fournisseur d'id ".$idFournisseur." n'existe pas.");
        }
         
        $form = $this->get('form.factory')->create(EditFournisseurType::class, $fournisseur);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
        {
           
            $em->flush();
            return $this->redirectToRoute('menu_fournisseurs');
        }
        return $this->render('Parametres/editerFournisseur.html.twig', array(
            'fournisseur' => $fournisseur,
            'form'   => $form->createView(),
        )); 
    }
    
    public function supprimerFournisseur($idFournisseur, Request $request){
        
        $em = $this->getDoctrine()->getManager();
        $fournisseur = $em->getRepository(Fournisseur::class)->find($idFournisseur);
        if (null === $fournisseur) {
            throw new NotFoundHttpException("Le médecin d'id ".$idFournisseur." n'existe pas.");
        }
        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        $form = $this->get('form.factory')->create();
       
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          $em->remove($fournisseur);
          $em->flush();
            
          $request->getSession()->getFlashBag()->add('info', "Le fournisseur a bien été supprimé.");

          return $this->redirectToRoute('menu_fournisseurs');
        }

        return $this->render('Parametres/supprimerFournisseur.html.twig', array(
          'fournisseur'=> $fournisseur,
          'form'   => $form->createView(),
        ));
    }

    public function menuMedecins()
    {
        $em = $this->getDoctrine()->getManager();
        $listMedecins = $em->getRepository(Medecin::class)->findAll();
        //die(var_dump($listMedecins));
         return $this->render('Parametres/menuMedecins.html.twig'
                 , array('listMedecins'=> $listMedecins ));
    }
    
    public function ficheMedecin($idMedecin)
    {
        $em = $this->getDoctrine()->getManager();
        $medecin = $em->getRepository(Medecin::class)->find($idMedecin);
        
        return $this->render('Parametres/ficheMedecin.html.twig'
                 , array('medecin'=> $medecin ));
    }
    
    public function ajouterMedecin(Request $request){
    
        $medecin = new Medecin();
        $form = $this->createForm(MedecinType::class, $medecin);
       
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $medecin->setNomsAffichage($medecin->getNom()." ".$medecin->getPrenom());
            $em->persist($medecin);
            $em->flush();
            
            $repository = $this->getDoctrine()->getRepository(Medecin::class);
            
            
            return $this->redirectToRoute('menu_medecins');
        }
        return $this->render('Parametres/ajouterMedecin.html.twig', array(
          'form' => $form->createView(),
        ));
    }
    
    public function editerMedecin($idMedecin, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $medecin = $em->getRepository(Medecin::class)->find($idMedecin);
        if (null === $medecin) {
            throw new NotFoundHttpException("Le médecin d'id ".$idMedecin." n'existe pas.");
        }
         
        $form = $this->get('form.factory')->create(EditMedecinType::class, $medecin);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
        {
           
            $em->flush();
            return $this->redirectToRoute('menu_medecins'
                        );
        }
        return $this->render('Parametres/editerMedecin.html.twig', array(
            'medecin' => $medecin,
            'form'   => $form->createView(),
        )); 
    }
    
    public function supprimerMedecin($idMedecin, Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $medecin = $em->getRepository(Medecin::class)->find($idMedecin);
        
        if (null === $medecin) {
            throw new NotFoundHttpException("Le médecin d'id ".$idMedecin." n'existe pas.");
        }
        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        $form = $this->get('form.factory')->create();
       
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          $em->remove($medecin);
          $em->flush();
            
          $request->getSession()->getFlashBag()->add('info', "Le médecin a bien été supprimée.");

          return $this->redirectToRoute('menu_medecins');
        }

        return $this->render('Parametres/supprimerMedecin.html.twig', array(
          'medecin' => $medecin,
          'form'   => $form->createView(),
        ));
    }
    
    public function menuCatProd()
    {
        $em = $this->getDoctrine()->getManager();
        $listCatProds = $em->getRepository(Categorie::class)->findAll();
        
         return $this->render('Parametres/menuCatProd.html.twig'
                 , array('listCatProds'=> $listCatProds ));
    }
    public function ajouterCatProd(Request $request){
    
        $catProd = new Categorie();
        $form = $this->createForm(CategorieType::class, $catProd);
       
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($catProd);
            $em->flush();
            
            return $this->redirectToRoute('menu_categories_produits');
        }
        return $this->render('Parametres/ajouterCatProd.html.twig', array(
          'form' => $form->createView(),
        ));
    }
    
    public function editerCatProd($idCatProd, Request $request){
        
        $em = $this->getDoctrine()->getManager();
        $catProd = $em->getRepository(Categorie::class)->find($idCatProd);
        if (null === $catProd) {
            throw new NotFoundHttpException("La categorie d'id ".$idCatProd." n'existe pas.");
        }
        
        $form = $this->get('form.factory')->create(EditCategorieType::class, $catProd);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
        {
            $em->flush();
            return $this->redirectToRoute('menu_categories_produits');
        }
        return $this->render('Parametres/editerCatProd.html.twig', array(
            'catProd' => $catProd,
            'form'   => $form->createView(),
        )); 
    }
    
    public function supprimerCatProd($idCatProd, Request $request ){
        
        $em = $this->getDoctrine()->getManager();
        $catProd = $em->getRepository(Categorie::class)->find($idCatProd);
        
        if (null === $catProd) {
            throw new NotFoundHttpException("La catégorie d'id ".$idCatProd." n'existe pas.");
        }
        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        $form = $this->get('form.factory')->create();
       
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          $em->remove($catProd);
          $em->flush();
            
          $request->getSession()->getFlashBag()->add('info', "La catégorie a bien été supprimée.");

          return $this->redirectToRoute('menu_categories_produits');
        }

        return $this->render('Parametres/supprimerCatProd.html.twig', array(
          'catProd' => $catProd,
          'form'   => $form->createView(),
        ));
    }
    
}
?>