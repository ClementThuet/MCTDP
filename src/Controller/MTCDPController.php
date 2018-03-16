<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Patient;
use App\Form\PatientType;
use App\Form\EditPatientType;

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
           /* return $this->render('Patients/menuPatients.html.twig', array(
            'form' => $form->createView(),
            'listPatients'=>$listPatients,
        ));*/
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
    
    public function fichePatient()
    {
        return $this->render('Patients/fichePatient.html.twig');
    }
    
    public function visitePatient()
    {
        return $this->render('Patients/visitePatient.html.twig');
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
}
