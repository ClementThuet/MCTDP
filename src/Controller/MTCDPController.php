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
use App\Form\DocumentVisiteType;
use App\Entity\Prescription;
use App\Form\PrescriptionType;
use App\Form\EditPrescriptionType;
use App\Entity\Produit;
use App\Entity\Medecin;
use App\Entity\Materiel;
use App\Entity\UtilisationMaterielVisite;
use App\Form\UtilisationMaterielVisiteType;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap4View;

class MTCDPController extends Controller{
    
    public function index()
    {
        return $this->render('login.html.twig');
    }
   
    
    public function mainMenu()
    {
        return $this->render('mainMenu.html.twig');
    }
    
    public function menuPatients($page, Request $request)
    {
        
        $qb = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->findAllQueryBuilder();
        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(3);
        $pagerfanta->setCurrentPage($page);
        $pagerfanta->haveToPaginate(); // whether the number of results is higher than the max per page

        $view = new TwitterBootstrap4View();
        $options = array('proximity' => 3,
            'prev_message'=>'<b><</b>',
            'next_message'=> '<b>></b>',
            'css_container_class' =>'pagination paginationOwn');

        $routeGenerator = function($page) {
            return 'page-'.$page;
        };

        $html = $view->render($pagerfanta, $routeGenerator, $options);
        $patients = [];
        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $patients[] = $result;
        }
        
        return $this->render('Patients/menuPatients.html.twig', array(
            'listPatients'=>$patients,
            'html' => $html));
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
            
            return $this->redirectToRoute('menu_patients',array('page'=>1));
        }
        return $this->render('Patients/ajouterPatient.html.twig', array(
          'form' => $form->createView()
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
        $medecinsPatient=$patient->getMedecins();
        $visite = new Visite();
        $document = new Document();
        $visite->getDocument()->add($document);
        //\Doctrine\Common\Util\Debug::dump($visite);
        $form = $this->createForm(VisiteType::class, $visite);
        
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
           
            $file= $document->getFile();
            if (null == $file) {
                var_dump('pas de document');
                $document=null;
            }
            else{
                // move takes the target directory and then the target filename to move to
                $fileName = $patient->getNom().'-'.$file->getClientOriginalName();
                $fileName = str_replace(' ', '-', $fileName);
                $fileName = htmlentities( $fileName, ENT_NOQUOTES, 'utf-8' );
                $fileName = preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $fileName );
                $fileName = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $fileName );
                $fileName = preg_replace( '#&[^;]+;#', '', $fileName );
                $absolutePath=$this->getParameter('photos_visite_directory').'/'.$fileName;
                $file->move(
                    $this->getParameter('photos_visite_directory'),
                        $fileName
                    );
                $document->setPatient($patient);
                $document->setVisite($visite);
                $document->setPath($fileName);
                $document->setAbsolutePath($absolutePath);
                $document->setDossier('photos_visite');
                $document->setDate($visite->getDate());
                
                $em->persist($document);
                
            }
            $visite->setDocument($document);
            $patient->addVisite($visite);
            $visite->setPatient($patient);
            $em->persist($visite);
            $em->persist($patient);
            $em->flush();
            
            return $this->redirectToRoute('fiche_visite',
                    array('idVisite'=> $visite->getId(),
                        ));
          
        } 
        return $this->render('Patients/visitePatient.html.twig', array(
          'form' => $form->createView(),
            'medecinsPatient' => $medecinsPatient,
            'patient' =>$patient,
            ));
    }
    public function ajouterPhotoVisite(Request $request,$idVisite)
    {
        $em = $this->getDoctrine()->getManager();
        $visite = $em->getRepository(Visite::class)->find($idVisite);
        $patient=$visite->getPatient();
         
        
        $document = new Document();
        $form = $this->createForm(DocumentVisiteType::class, $document);
        
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
            $absolutePath=$this->getParameter('photos_visite_directory').'/'.$fileName;
            $file->move(
                $this->getParameter('photos_visite_directory'),
                $fileName);
            
            $visite->setDocument($document);
            $document->setPatient($patient);
            $document->setVisite($visite);
            $document->setPath($fileName);
            $document->setAbsolutePath($absolutePath);
            $document->setDate($visite->getDate());
            $em->persist($document);
            $em->persist($visite);
            //$em->persist($patient);
            $em->flush();
            
            return $this->redirectToRoute('fiche_visite',
                    array('idVisite'=> $idVisite,
                        'patient'=>$patient,
                        ));
          
        }
        return $this->render('Patients/ajouterPhotoVisite.html.twig', array(
          'form' => $form->createView(),
            'visite' =>$visite,
            ));
    }
    
    public function supprimerPhotoVisite(Request $request,$idVisite)
    {
        $visite = $this->getDoctrine()
        ->getRepository(Visite::class)->find($idVisite);
        $document= $visite->getDocument();
        $patient=$visite->getPatient();
        $pathFileToRemove=$document->getAbsolutePath();
        if ( file_exists($pathFileToRemove)) {
            // On crée un formulaire vide, qui ne contiendra que le champ CSRF
            $form = $this->get('form.factory')->create();

            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
              $em = $this->getDoctrine()->getManager();
              $em->remove($document);
              $visite->setDocument(null);
              $em->flush();
              unlink($pathFileToRemove);
              $request->getSession()->getFlashBag()->add('info', "Le document a bien été supprimé.");

              return $this->redirectToRoute('fiche_visite',
                        array('idVisite' => $idVisite
                            ));
            }

            return $this->render('Patients/supprimerPhotoVisite.html.twig', array(
              'visite' => $visite,
              'patient'=>$patient,
              'document'=> $document,
              'form'   => $form->createView(),
            ));

        }
    }
    
    public function choixMaterielVisite($idVisite,$page,Request $request){
           
        $em = $this->getDoctrine()->getManager();
        $visite = $em->getRepository(Visite::class)->find($idVisite);
        
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
             'prev_message'=>'<b><</b>',
            'next_message'=> '<b>></b>',
            'css_container_class' =>'pagination paginationOwn');

        $routeGenerator = function($page) {
            return 'page-'.$page;
        };

        $html = $view->render($pagerfanta, $routeGenerator, $options);
        $materiels = [];
        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $materiels[] = $result;
        }
        
        return $this->render('Patients/choixMaterielVisite.html.twig', array(
            'idVisite'=> $idVisite,
            'visite'=>$visite,
            'listMateriels'=>$materiels,
            'html'=>$html,
        )); 
    }
    
    public function ajouterMaterielVisite($idVisite,$idMateriel){
           
       
        return $this->redirectToRoute('choix_qte_materiel_visite',
                    array('idVisite'=> $idVisite,
                        'idMateriel'=>$idMateriel,
                        ));
    }
    
    public function choixQteMaterielVisite($idVisite, $idMateriel, Request $request){
           
        
        $em = $this->getDoctrine()->getManager();
        $visite = $em->getRepository(Visite::class)->find($idVisite);
        $materiel = $em->getRepository(Materiel::class)->find($idMateriel);
        
        $utilisationMaterielVisite = new UtilisationMaterielVisite();
        $form = $this->createForm(UtilisationMaterielVisiteType::class, $utilisationMaterielVisite);
        
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            
            //$visite->addMateriel($materiel);
            $visite->addUtilisationMaterielVisite($utilisationMaterielVisite);
            
            //Vérification quantité en stock > qté demandée
            $quantite=$utilisationMaterielVisite->getQuantite();
            $enStock=$materiel->getQteStock();
            if ($enStock-$quantite<0)
            {
              $this->addFlash("warning", "Attention, quantité en stock inférieur à quantité demandée, stock désormais négatif, vérifiez votre stock !");
            }
            $materiel->setQteStock($enStock-$quantite);
            
            $utilisationMaterielVisite->setVisite($visite);
            $utilisationMaterielVisite->setMateriel($materiel);
            $em->persist($utilisationMaterielVisite);
            $em->persist($visite);
            $em->persist($materiel);
            $em->flush();
            
            $listMateriels=[];
            $qb = $em->getRepository(UtilisationMaterielVisite::class)->findByVisite($idVisite);
            $query = $qb->getQuery();
            $listUtilMatVisi = $query->getResult();
            for($i=0;$i<count($listUtilMatVisi);$i++)
            {
                
                array_push($listMateriels,$listUtilMatVisi[$i]->getMateriel());
            }
            
            return $this->redirectToRoute('fiche_visite',array(
                'idVisite'=> $visite->getId(),
                'visite'=>$visite,
                'listMateriels'=>$listMateriels));
        }
        //SI on ne veut pas 2 materiels identique pour une visite
        //Si materiel n'existe pas déja dans la visite
       /* $listMaterielVisite=$visite->getMateriels();
        $occurence=0;
        for($j=0;$j<count($listMaterielVisite);$j++){
            if($idMateriel == $listMaterielVisite[$j]->getId()){
                $occurence+=1;
            }
            else
            {
                $occurence+=0;
            }
        }
        //Si il n'existe pas on demande la quantité ...
        if($occurence ==0)
        {*/
            return $this->render('Patients/choixQteMaterielVisite.html.twig', array(
            'form' => $form->createView(),
            'visite' =>$visite,
            'materiel' =>$materiel,
            ));
       /* }
        //Sinon on redirige vers la fiche visite avec un message d'erreur
        else 
        {
            $this->addFlash("warning", "Produit déjà existant dans la fiche visite !");
             return $this->redirectToRoute('fiche_visite',array(
                'idVisite'=> $visite->getId(),
                )); 
        }*/
    }
    public function retirerMaterielVisite($idUtilMatVisite){
           
        $em = $this->getDoctrine()->getManager();
        $utilToDelete = $em->getRepository(UtilisationMaterielVisite::class)->find($idUtilMatVisite);
        $visite=$utilToDelete->getVisite()->getId();
        $materiel=$utilToDelete->getMateriel();
        $em->remove($utilToDelete);
        
        $quantite=$utilToDelete->getQuantite();
        $stock=$materiel->getQteStock();
        $materiel->setQteStock($stock+$quantite);
        $em->persist($materiel);
        $em->flush();
        /*$materiel = $em->getRepository(Materiel::class)->find($idMateriel);
        $visite = $em->getRepository(Visite::class)->find($idVisite);
        $listUtilisations=$visite->getUtilisationsMaterielVisite();
        for ($i=0;$i<count($listUtilisations);$i++)
        {
           if($listUtilisations[$i]->getMateriel()->getId() == $idMateriel && $listUtilisations[$i]->getMateriel()->getId()){
                $quantite=$listUtilisations[$i]->getQuantite();
                $stock=$materiel->getQteStock();
                $materiel->setQteStock($stock+$quantite);
                $em->persist($materiel);
                if ($listUtilisations[$i] != null){
                    var_dump($idMateriel);
                    die(var_dump($listUtilisations[$i]->getId()));
                    $em->remove($listUtilisations[$i]);
                    //$em->flush();
                }
            }
        }
        //$visite->removeMateriel($materiel);
        $em->persist($visite);*/
       
        
        return $this->redirectToRoute('fiche_visite',array(
                'idVisite'=> $visite)); 
    }
    
    //Création d'un réglement, edit et suppr ds ComptaController
    public function reglementVisite(Request $request,$idVisite,$modeRegl)
    {
        $em = $this->getDoctrine()->getManager();
        $visite = $em->getRepository(Visite::class)->find($idVisite);
        
        $patient=$visite->getPatient();
        $reglement = new Reglement();
        if($modeRegl=="cheque"){$modeRegl="Chèque";} else {$modeRegl="Espèces";};
        $reglement->setModeReglement($modeRegl);
        $form = $this->createForm(ReglementType::class, $reglement);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
             
            $reglement->setVisite($visite);
            $reglement->setOrigine('Médecine chinoise');
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
    public function ficheVisite($idVisite,Request $request){
       
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
        
        $listMateriels=[];
        $qb = $em->getRepository(UtilisationMaterielVisite::class)->findByVisite($idVisite);
        $query = $qb->getQuery();
        $listUtilMatVisi = $query->getResult();
        for($i=0;$i<count($listUtilMatVisi);$i++)
        {

            array_push($listMateriels,$listUtilMatVisi[$i]->getMateriel());
        }
        $request->getSession()->set('urlRetourFicheRegl', "fiche_visite");
        $request->getSession()->set('urlRetourFicheReglId', $idVisite);
        return $this->render('Patients/ficheVisite.html.twig', array(
            'patient' => $patient,
            'visite'=>$visite,
            'existPrescription'=>$existPrescription,
            'prescription'=>$prescription,
            'existReglement'=>$existReglement,
            'reglement'=>$reglement,
            'listMateriels'=>$listMateriels,
                 ));
    }
    
    public function rechercherMaterielVisite($idVisite,$Entite, $champ, $valeur){
        $em = $this->getDoctrine()->getManager();
        $visite = $em->getRepository(Visite::class)->find($idVisite);
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
                ->select('m')
                ->from('App\Entity\Materiel', 'm')   
                ->innerJoin('m.categorie', 'cat', 'WITH', 'cat.id = :valeur')
                ->setParameter('valeur', ''.$ids.'');
                $query2 = $qb2 ->getQuery();
                $listMateriels = $query2->getResult();
            }
            else{
                $listMateriels='';
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
             $listMateriels = $query->getResult();
        }
        //\Doctrine\Common\Util\Debug::dump($ids);
        return $this->render('Patients/choixMaterielVisite.html.twig', array(
            'listMateriels'=>$listMateriels,
            'rechercheEffectuee'=>1,
            'visite'=>$visite,
        ));
    }
    
    public function historiqueVisites($page, Request $request){
        
       $page = $request->query->get('page', $page);
        
        $qb = $this->getDoctrine()
            ->getRepository(Visite::class)
            ->findAllQueryBuilder();
        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(3);
        $pagerfanta->setCurrentPage($page);
        $pagerfanta->haveToPaginate(); // whether the number of results is higher than the max per page

        $view = new TwitterBootstrap4View();
        $options = array('proximity' => 3,
             'prev_message'=>'<b><</b>',
            'next_message'=> '<b>></b>',
            'css_container_class' =>'pagination paginationOwn');

        $routeGenerator = function($page) {
            return 'page-'.$page;
        };

        $html = $view->render($pagerfanta, $routeGenerator, $options);
        $listVisites = [];
        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $listVisites[] = $result;
        }
        
        return $this->render('Patients/historiqueVisites.html.twig',array( 
            'listVisites'=>$listVisites,
             'html' => $html));
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
                        'page'=>1
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
                    array('idPatient' => $patient->getId(),
                        'page'=>1
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
                    $fileName);
           
            $em = $this->getDoctrine()->getManager();
            $patient->addDocument($document);
            $document->setPatient($patient);
            // path est le nom du fichier car dans twig on indique le dossier uploads/documents
            $document->setPath($fileName);
            $document->setAbsolutePath($absolutePath);
            $document->setDossier('documents');
            $em->persist($document);
            $em->flush();

            return $this->redirectToRoute('documents_patient',array( 
            'idPatient'=>$idPatient,
            'patient'=>$patient,
            'listDocuments'=>$listDocuments,       
            'page'=>1
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
                                        'listDocuments'=>$listDocuments,
                                    'page'=>1
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
                'listDocuments'=>$listDocuments,
                'page'=>1
                    ));

        }
    
    public function noteHonoraire($idVisite,$montant,$typeVisite,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $visite = $em->getRepository(Visite::class)->find($idVisite);
        $patient=$visite->getPatient();
        
        
       return $this->render('Patients/noteHonoraire.html.twig', array(
            'visite'=> $visite,
            'patient'=> $patient,
           'montant'=>$montant,
           'typeVisite'=>$typeVisite,
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
            //$visite->addPrescription($prescription);
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
            'idVisite'=>$idVisite,
            'form'   => $form->createView(),
        )); 
    }
        
    public function historiquePrescriptions($page, Request $request){
        
       $page = $request->query->get('page', $page);
        
        $qb = $this->getDoctrine()
            ->getRepository(Prescription::class)
            ->findAllQueryBuilder();
        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(3);
        $pagerfanta->setCurrentPage($page);
        $pagerfanta->haveToPaginate(); // whether the number of results is higher than the max per page

        $view = new TwitterBootstrap4View();
        $options = array('proximity' => 3,
            'prev_message'=>'<b><</b>',
            'next_message'=> '<b>></b>',
            'css_container_class' =>'pagination paginationOwn');

        $routeGenerator = function($page) {
            return 'page-'.$page;
        };

        $html = $view->render($pagerfanta, $routeGenerator, $options);
        $listPrescriptions = [];
        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $listPrescriptions[] = $result;
        }
        
        return $this->render('Patients/historiquePrescription.html.twig',array(
                'listPrescriptions'=>$listPrescriptions,
                'page'=>1,
                'html' => $html));
    }
    
    public function historiquePrescriptionsPatient($idPatient,$page, Request $request){
        
        $em = $this->getDoctrine()->getManager();
        $patient = $em->getRepository(Patient::class)->find($idPatient);
        
        $page = $request->query->get('page', $page);
        
        $qb = $this->getDoctrine()
            ->getRepository(Prescription::class)
            ->findPrescriptionsPatientQueryBuilder($idPatient);
       
        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(3);
        $pagerfanta->setCurrentPage($page);
        $pagerfanta->haveToPaginate(); // whether the number of results is higher than the max per page

        $view = new TwitterBootstrap4View();
        $options = array('proximity' => 3,
             'prev_message'=>'<b><</b>',
            'next_message'=> '<b>></b>',
            'css_container_class' =>'pagination paginationOwn');

        $routeGenerator = function($page) {
            return 'page-'.$page;
        };

        $html = $view->render($pagerfanta, $routeGenerator, $options);
        $listPrescriptions = [];
        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $listPrescriptions[] = $result;
        }
        //var_dump($listPrescriptions);
        return $this->render('Patients/historiquePrescription.html.twig',array(
                'listPrescriptions'=>$listPrescriptions,
                'page'=>1,
                'patient'=>$patient,
                'html' => $html));
    }
    
    public function choixProduitPrescription($idPrescription,$page,Request $request){
           
        $em = $this->getDoctrine()->getManager();
        $prescription = $em->getRepository(Prescription::class)->find($idPrescription);
        
        $qb = $this->getDoctrine()
            ->getRepository(Produit::class)
            ->findAllQueryBuilder();
        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(7);
        $pagerfanta->setCurrentPage($page);
        $pagerfanta->haveToPaginate(); // whether the number of results is higher than the max per page

        $view = new TwitterBootstrap4View();
        $options = array('proximity' => 3,
             'prev_message'=>'<b><</b>',
            'next_message'=> '<b>></b>',
            'css_container_class' =>'pagination paginationOwn');

        $routeGenerator = function($page) {
            return 'page-'.$page;
        };

        $html = $view->render($pagerfanta, $routeGenerator, $options);
        $listProduits = [];
        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $listProduits[] = $result;
        }
        
        return $this->render('Patients/choixProduitPrescription.html.twig', array(
            'idPrescription'=> $idPrescription,
            'prescription'=>$prescription,
            'listProduits'=>$listProduits,
            'html'=>$html
        )); 
    }
    
    public function rechercherProduitPrescription($idPrescription,$Entite, $champ, $valeur){
        $em = $this->getDoctrine()->getManager();
        $prescription = $em->getRepository(Prescription::class)->find($idPrescription);
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
        return $this->render('Patients/choixProduitPrescription.html.twig', array(
            'listProduits'=>$listProduits,
            'rechercheEffectuee'=>1,
            'prescription'=>$prescription,
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
            return $this->redirectToRoute('historique_prescriptions',array('page'=>1));
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

          return $this->redirectToRoute('historique_prescriptions',array('page'=>1));
        }

        return $this->render('Patients/supprimerPrescription.html.twig', array(
          'prescription' => $prescription,
          'form'   => $form->createView(),
        ));
    }


    
    
    
    
    
   
    
}   
