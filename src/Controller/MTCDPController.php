<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Patient;
use App\Form\PatientType;
use App\Form\EditPatientType;
use App\Entity\Visite;
use App\Form\VisiteType;
use App\Form\EditVisiteType;
use App\Entity\Fournisseur;
use App\Form\FournisseurType;
use App\Form\EditFournisseurType;
use App\Entity\Medecin;
use App\Form\MedecinType;
use App\Form\EditMedecinType;

class MTCDPController extends Controller{
    
    public function index()
    {
        return $this->render('login.html.twig');
    }
    
    public function mainMenu()
    {
        return $this->render('mainMenu.html.twig');
    }
    
    public function menuPatients()
    {
        $repository = $this->getDoctrine()->getRepository(Patient::class);
        $listPatients = $repository->findAll();

        return $this->render('Patients/menuPatients.html.twig', array('listPatients'=>$listPatients));
    }
    
    public function ajouterPatient(Request $request)
    {
        
        $patient = new Patient();
        $form = $this->createForm(PatientType::class, $patient);
       
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($patient);
            $em->flush();
            
            $repository = $this->getDoctrine()->getRepository(Patient::class);
            $listPatients = $repository->findAll();
            
            return $this->redirectToRoute('menu_patients');
        }
        return $this->render('Patients/ajouterPatient.html.twig', array(
          'form' => $form->createView(),
        ));
    }
    
    public function editerPatient($id, Request $request){
        
        $em = $this->getDoctrine()->getManager();
        $patient = $em->getRepository(Patient::class)->find($id);
        
        if (null === $patient) {
            throw new NotFoundHttpException("Le  patient d'id ".$id." n'existe pas.");
        }
        
        $form = $this->get('form.factory')->create(EditPatientType::class, $patient);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
        {
            $em->flush();
            return $this->redirectToRoute('fiche_patient', array('id' => $patient->getId()));
        }
        return $this->render('Patients/editerPatient.html.twig', array(
            'patient' => $patient,
            'form'   => $form->createView(),
        )); 
    }
    
    public function supprimerPatient($id, Request $request ){
        
        $em = $this->getDoctrine()->getManager();
        $patient = $em->getRepository(Patient::class)->find($id);
        
        if (null === $patient) {
            throw new NotFoundHttpException("Le  patient d'id ".$id." n'existe pas.");
        }
        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        $form = $this->get('form.factory')->create();
       
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          $em->remove($patient);
          $em->flush();
            
          $request->getSession()->getFlashBag()->add('info', "Le patient a bien été supprimé.");

          return $this->redirectToRoute('menu_patients');
        }

        return $this->render('Patients/supprimerPatient.html.twig', array(
          'patient' => $patient,
          'form'   => $form->createView(),
        ));
    }
    
    public function fichePatient($id)
    {
         $em = $this->getDoctrine()->getManager();
        $patient = $em->getRepository(Patient::class)->find($id);
        
        if (null === $patient) {
            throw new NotFoundHttpException("Le  patient d'id ".$id." n'existe pas.");
        }
        return $this->render('Patients/fichePatient.html.twig', array(
            'patient' => $patient));
    }
    
    public function visitePatient($idPatient,Request $request)
    {
         
        $em = $this->getDoctrine()->getManager();
        $patient = $em->getRepository(Patient::class)->find($idPatient);
        
        if (null === $patient) {
            throw new NotFoundHttpException("Le  patient d'id ".$id." n'existe pas.");
        }
        
        $visite = new Visite();
        $form = $this->createForm(VisiteType::class, $visite);
        
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $patient->addVisite($visite);
            $visite->setPatient($patient);
            $em->persist($visite);
            $em->flush();
            
           // $repository = $this->getDoctrine()->getRepository(Patient::class);
           // $listPatients = $repository->findAll();
            
            return $this->redirectToRoute('menu_patients');
          
        }
        return $this->render('Patients/visitePatient.html.twig', array(
          'form' => $form->createView(),
            'patient' =>$patient,
            ));
    }
    
    public function historiqueVisitePatient($idPatient){
        
        //Recherche du patient dont on veut afficher l'historique
        $patient = $this->getDoctrine()
                ->getRepository(Patient::class)->find($idPatient);
        
        //Recherche des visites du patient dont on veut afficher l'historique
        $listVisites=$patient->getVisites();
        return $this->render('Patients/historiqueVisitePatient.html.twig',array( 
            'patient'=>$patient,
            'listVisites'=>$listVisites
             ));
    }
    
    public function editerVisite($idVisite,Request $request){
           
        $em = $this->getDoctrine()->getManager();
        $visite = $em->getRepository(Visite::class)->find($idVisite);
        $patient=$visite->getPatient();
        //die(var_dump($visite->getDate()));
        if (null === $visite) {
            throw new NotFoundHttpException("La visite d'id ".$id." n'existe pas.");
        }
         
        $form = $this->get('form.factory')->create(EditVisiteType::class, $visite);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
        {
           
            $em->flush();
            return $this->redirectToRoute('historique_visite_patient',
                    array('idPatient' => $patient->getId(),
                        'visite'=> $visite,
                        ));
        }
        return $this->render('Patients/editerVisite.html.twig', array(
            'visite'=> $visite,
            'patient' => $patient,
            'form'   => $form->createView(),
        )); 
    }
    
    public function supprimerVisite($idVisite,Request $request){
        
        $em = $this->getDoctrine()->getManager();
        $visite = $em->getRepository(Visite::class)->find($idVisite);
        $patient=$visite->getPatient();
        if (null === $visite) {
            throw new NotFoundHttpException("La visite d'id ".$id." n'existe pas.");
        }
        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        $form = $this->get('form.factory')->create();
       
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          $em->remove($visite);
          $em->flush();
            
          $request->getSession()->getFlashBag()->add('info', "La visite a bien été supprimée.");

          return $this->redirectToRoute('historique_visite_patient',
                    array('idPatient' => $patient->getId()
                        ));
        }

        return $this->render('Patients/supprimerVisite.html.twig', array(
          'patient' => $patient,
          'visite'=> $visite,
          'form'   => $form->createView(),
        ));
    }
   
    public function menuMateriel()
    {
        return $this->render('Materiel/menuMateriel.html.twig');
    }
    
    public function menuQiGong()
    {
        return $this->render('QiGong/menuQiGong.html.twig');
    }
    
    public function menuComptabilite()
    {
        return $this->render('Comptabilite/menuComptabilite.html.twig');
    }
    
    public function menuParametres()
    {
        return $this->render('Parametres/menuParametres.html.twig');
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
    
    }
    
}   
