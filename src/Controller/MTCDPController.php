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
use App\Entity\Reglement;
use App\Form\ReglementType;
use App\Entity\Document;
use App\Form\DocumentType;
use App\Entity\Prescription;
use App\Form\PrescriptionType;
use App\Form\EditPrescriptionType;
use App\Entity\Produit;
use App\Entity\Medecin;

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
            $patient->setNomsAffichage($patient->getNom()." ".$patient->getPrenom());
            $patient->setActif(true);
            $em->persist($patient);
            $em->flush();
            
           
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
            $patient->setNomsAffichage($patient->getNom()." ".$patient->getPrenom());
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
        $medecinsPatient=$patient->getMedecins();
        $ListVisites=$patient->getVisites();
        $derniereVisite=$ListVisites->last();
        if (null === $patient) {
            throw new NotFoundHttpException("Le  patient d'id ".$id." n'existe pas.");
        }
        return $this->render('Patients/fichePatient.html.twig', array(
            'derniereVisite'=>$derniereVisite,
            'patient' => $patient,
            'medecinsPatient' => $medecinsPatient,
            ));
    }
    
    public function choixMedecinPatient($idPatient,Request $request){
           
        $em = $this->getDoctrine()->getManager();
        $patient = $em->getRepository(Patient::class)->find($idPatient);
        $listMedecins = $em->getRepository(Medecin::class)->findAll();
        
        return $this->render('Patients/choixMedecinPatient.html.twig', array(
            'idPatient'=> $idPatient,
            'patient'=>$patient,
            'listMedecins'=>$listMedecins,
        )); 
    }
    
    public function ajouterMedecinPatient($idPatient,$idMedecin){
           
        $em = $this->getDoctrine()->getManager();
        $patient = $em->getRepository(Patient::class)->find($idPatient);
        $medecin = $em->getRepository(Medecin::class)->find($idMedecin);
        
        $patient->addMedecin($medecin);
        $em->persist($patient);
        $em->flush();
        return $this->redirectToRoute('fiche_patient',array(
                'id'=> $patient->getId(),
        )); 
    }
    
    public function retirerMedecinPatient($idPatient,$idMedecin){
           
        $em = $this->getDoctrine()->getManager();
        $patient = $em->getRepository(Patient::class)->find($idPatient);
        $medecin = $em->getRepository(Medecin::class)->find($idMedecin);
        
        $patient->removeMedecin($medecin);
        $em->persist($patient);
        $em->flush();
        return $this->redirectToRoute('fiche_patient',array(
                'id'=> $patient->getId(),
        )); 
    }
    
    //Creation visite
    public function visitePatient($idPatient,Request $request)
    {
         
        $em = $this->getDoctrine()->getManager();
        $patient = $em->getRepository(Patient::class)->find($idPatient);
        
        if (null === $patient) {
            throw new NotFoundHttpException("Le  patient d'id ".$idPatient." n'existe pas.");
        }
        
        $visite = new Visite();
        $form = $this->createForm(VisiteType::class, $visite);
        
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $patient->addVisite($visite);
            $visite->setPatient($patient);
            $em->persist($visite);
            $em->flush();
            
            return $this->redirectToRoute('fiche_visite',
                    array('idVisite'=> $visite->getId(),
                        ));
          
        }
        return $this->render('Patients/visitePatient.html.twig', array(
          'form' => $form->createView(),
            'patient' =>$patient,
            ));
    }
    //Création d'un réglement, edit et suppr ds ComptaController
    public function reglementVisite(Request $request,$idVisite,$modeRegl)
    {
        $em = $this->getDoctrine()->getManager();
        $visite = $em->getRepository(Visite::class)->find($idVisite);
        
        $patient=$visite->getPatient();
        $reglement = new Reglement();
        if($modeRegl=="cheque"){$modeRegl="Chèque";} else {$modeRegl="Espèce";};
        $reglement->setModeReglement($modeRegl);
        $form = $this->createForm(ReglementType::class, $reglement);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
             
            $reglement->setVisite($visite);
            $em = $this->getDoctrine()->getManager();
            $em->persist($reglement);
            $em->flush();
            
            return $this->redirectToRoute('fiche_visite',array(
                'idVisite'=> $visite->getId(),
            ));
        }
        return $this->render('Patients/reglementVisite.html.twig', array(
            'form' => $form->createView(),
            'patient' => $patient,
            'modeDeReglement'=>$reglement->getModeReglement(),
            'visite'=>$visite
                 ));
     }
    //Affichage d'une fiche visite (historique)
    public function ficheVisite($idVisite){
        $em = $this->getDoctrine()->getManager();
        $visite = $em->getRepository(Visite::class)->find($idVisite);
        
        //Recherche si il existe au moins un réglement 
        $reglementsVisite=$visite->getReglements();
        if(empty($reglementsVisite[0])){$existReglement=0;
            $reglement=null;
        }
        else {$existReglement=1;
        $reglement=$reglementsVisite[0];}
        
        //Recherche si il existe au moins une prescription 
        $prescriptionsVisite=$visite->getPrescription();
        if(empty($prescriptionsVisite[0])){$existPrescription=0;
        $prescription=null;}
        else {$existPrescription=1;
        $prescription=$prescriptionsVisite[0];}
        
        $patient=$visite->getPatient();
        return $this->render('Patients/ficheVisite.html.twig', array(
            'patient' => $patient,
            'visite'=>$visite,
            'existPrescription'=>$existPrescription,
            'prescription'=>$prescription,
            'existReglement'=>$existReglement,
            'reglement'=>$reglement,
                 ));
    }
    
    public function historiqueVisites(){
        
        $listVisites = $this->getDoctrine()
        ->getRepository(Visite::class)->findAll();
        
        return $this->render('Patients/historiqueVisites.html.twig',array( 
            'listVisites'=>$listVisites,
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
        if (null === $visite) {
            throw new NotFoundHttpException("La visite d'id ".$idVisite." n'existe pas.");
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
            throw new NotFoundHttpException("La visite d'id ".$idVisite." n'existe pas.");
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
   
    public function documentsPatient($idPatient){
        
        $patient = $this->getDoctrine()
        ->getRepository(Patient::class)->find($idPatient);
        
        $listDocuments= $patient->getDocuments(); 
        return $this->render('Patients/documentsPatient.html.twig',array( 
            'patient'=>$patient,
            'idPatient'=>$patient->getId(),
            'listDocuments'=>$listDocuments
             ));
    }
    public function ajouterDocument(Request $request, $idPatient){
        
        $em = $this->getDoctrine()->getManager();
        $patient = $em->getRepository(Patient::class)->find($idPatient);
        $listDocuments= $patient->getDocuments(); 
        if (null === $patient) {
            throw new NotFoundHttpException("Le  patient d'id ".$idPatient." n'existe pas.");
        }
        
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            
            $file= $document->getFile();
            if (null === $file) {
                return;
            }
            // move takes the target directory and then the target filename to move to
            $fileName = $patient->getNom().'-'.$file->getClientOriginalName();
            $fileName = str_replace(' ', '-', $fileName);
            $fileName = htmlentities( $fileName, ENT_NOQUOTES, 'utf-8' );
            $fileName = preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $fileName );
            $fileName = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $fileName );
            $fileName = preg_replace( '#&[^;]+;#', '', $fileName );
            $absolutePath=$this->getParameter('documents_directory').'/'.$fileName;
            $file->move(
                $this->getParameter('documents_directory'),
                    $fileName
                );
           
            $em = $this->getDoctrine()->getManager();
            $patient->addDocument($document);
            $document->setPatient($patient);
            // path est le nom du fichier car dans twig on indique le dossier uploads/documents
            $document->setPath($fileName);
            $document->setAbsolutePath($absolutePath);
            $em->persist($document);
            $em->flush();

            return $this->redirectToRoute('documents_patient',array( 
            'idPatient'=>$idPatient,
            'patient'=>$patient,
            'listDocuments'=>$listDocuments
                ));
            }

         return $this->render('Patients/ajouterDocument.html.twig', array(
          'form' => $form->createView(),
             'patient'=>$patient,
        ));
    }
    public function supprimerDocument(Request $request, $idDocument){   
        $document = $this->getDoctrine()
            ->getRepository(Document::class)->find($idDocument);
            $patient= $document->getPatient();
            $listDocuments= $patient->getDocuments(); 

            $pathFileToRemove=$document->getAbsolutePath();
            if ( file_exists($pathFileToRemove)) {


                if ($patient->getDocuments()->contains($document)) {

                    $em = $this->getDoctrine()->getManager();
                    $document = $em->getRepository(Document::class)->find($idDocument);

                    if (null === $document) {
                        throw new NotFoundHttpException("Le document d'id ".$idDocument." n'existe pas.");
                    }
                    // On crée un formulaire vide, qui ne contiendra que le champ CSRF
                    $form = $this->get('form.factory')->create();

                    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                      $em->remove($document);
                      $em->flush();
                      unlink($pathFileToRemove);
                      $request->getSession()->getFlashBag()->add('info', "Le document a bien été supprimé.");

                      return $this->redirectToRoute('documents_patient',
                                array('idPatient' => $patient->getId(),
                                        'listDocuments'=>$listDocuments
                                    ));
                    }

                    return $this->render('Patients/supprimerDocument.html.twig', array(
                      'patient' => $patient,
                      'document'=> $document,
                      'form'   => $form->createView(),
                    ));

                }
            }

            return $this->redirectToRoute('documents_patient',array( 
                'idPatient'=>$patient->getId(),
                'patient'=>$patient,
                'listDocuments'=>$listDocuments
                    ));

        }
    
        
    public function fichePrescription($idPrescription)
    {
       $em = $this->getDoctrine()->getManager();
       $prescription = $em->getRepository(Prescription::class)->find($idPrescription);
       //\Doctrine\Common\Util\Debug::dump(($prescription->getProduits()));
        return $this->render('Patients/fichePrescription.html.twig',array(
                'prescription'=>$prescription,
            ));
    }
    public function creerPrescription($idVisite,Request $request)
    {
        
        $prescription = new Prescription();
        $form = $this->createForm(PrescriptionType::class, $prescription);
        $em = $this->getDoctrine()->getManager();
        $visite = $em->getRepository(Visite::class)->find($idVisite);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
           
            $prescription->setVisite($visite);
           // $visite->addPrescription($prescription);
            $em->persist($prescription);
            //$em->persist($visite);
            $em->flush();
            
            
            return $this->redirectToRoute('fiche_prescription',array(
                'idPrescription'=> $prescription->getId(),
                'prescription'=>$prescription,
            ));
        }
        return $this->render('Patients/creerPrescription.html.twig', array(
            'prescription'=> $prescription,
            'form'   => $form->createView(),
        )); 
    }
        
    public function historiquePrescriptions()
    {
       $em = $this->getDoctrine()->getManager();
       $listPrescriptions = $em->getRepository(Prescription::class)->findAll();
      
        return $this->render('Patients/historiquePrescription.html.twig',array(
                'listPrescriptions'=>$listPrescriptions,
            ));
    }
    
    public function choixProduitPrescription($idPrescription,Request $request){
           
        $em = $this->getDoctrine()->getManager();
        $prescription = $em->getRepository(Prescription::class)->find($idPrescription);
        $listProduits = $em->getRepository(Produit::class)->findAll();
        
        return $this->render('Patients/choixProduitPrescription.html.twig', array(
            'idPrescription'=> $idPrescription,
            'prescription'=>$prescription,
            'listProduits'=>$listProduits,
        )); 
    }
    
    public function ajouterProduitPrescription($idPrescription,$idProduit){
           
        $em = $this->getDoctrine()->getManager();
        $prescription = $em->getRepository(Prescription::class)->find($idPrescription);
        $produit = $em->getRepository(Produit::class)->find($idProduit);

        $prescription->addProduit($produit);
        $em->persist($prescription);
        $em->flush();
        
        return $this->redirectToRoute('fiche_prescription',array(
                'idPrescription'=> $prescription->getId(),
                'prescription'=>$prescription,
        )); 
    }
    
     public function retirerProduitPrescription($idProduit,$idPrescription){
           
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(Produit::class)->find($idProduit);
        $prescription = $em->getRepository(prescription::class)->find($idPrescription);
        
       $prescription->removeProduit($produit);
        $em->persist($prescription);
        $em->flush();
        return $this->redirectToRoute('fiche_prescription',array(
                'idPrescription'=> $prescription->getId(),
        )); 
    }
    
    
    public function editerPrescription(Request $request, $idPrescription)
    {
        $em = $this->getDoctrine()->getManager();
        $prescription = $em->getRepository(Prescription::class)->find($idPrescription);
        if (null === $prescription) {
            throw new NotFoundHttpException("La prescription d'id ".$idPrescription." n'existe pas.");
        }
         
        $form = $this->get('form.factory')->create(EditPrescriptionType::class, $prescription);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
        {
            $em->flush();
            return $this->redirectToRoute('historique_prescriptions');
        }
        return $this->render('Patients/editerPrescription.html.twig', array(
            'prescription'=> $prescription,
            'form'   => $form->createView(),
        )); 
    }
    
    public function supprimerPrescription(Request $request, $idPrescription)
    {
        $em = $this->getDoctrine()->getManager();
        $prescription = $em->getRepository(Prescription::class)->find($idPrescription);
        if (null === $prescription) {
            throw new NotFoundHttpException("La prescription d'id ".$idPrescription." n'existe pas.");
        }
        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        $form = $this->get('form.factory')->create();
       
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          //$visite=$prescription->getPrescription();
          //$visite->setPrescription(null);
          $em->remove($prescription);
          $em->flush();
            
          $request->getSession()->getFlashBag()->add('info', "La visite a bien été supprimée.");

          return $this->redirectToRoute('historique_prescriptions');
        }

        return $this->render('Patients/supprimerPrescription.html.twig', array(
          'prescription' => $prescription,
          'form'   => $form->createView(),
        ));
    }


    
    
    
    
    
   
    
}   
