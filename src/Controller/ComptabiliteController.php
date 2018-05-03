<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Reglement;
use App\Form\EditReglementType;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        //die(var_dump($reglement->getModeReglement()));
        return $this->render('Comptabilite/editerReglement.html.twig', array(
            'reglement' => $reglement,
            'modeReglement'=>$reglement->getModeReglement(),
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
    
    public function filtrerReglements(Request $request)
    {
        
        if(1==1)
        {
            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder();
            $qb->select('r ')
            ->from('App\Entity\Reglement', 'r')
            ->where('r.encaisse = 0 ')
            ->orderBy('r.date', 'DESC');
        }
        if($request->get('inputNnEncaisse') == "true")
        {
            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder();
            $qb->select('r ')
            ->from('App\Entity\Reglement', 'r')
            ->where('r.encaisse = 1 ')
            ->orderBy('r.date', 'DESC');
        }
        
        $query = $qb->getQuery();
        $listReglements = $query->getResult();
        $tabReglements=[];
        for($i=0;$i<count($listReglements);$i++)
        {
            if($listReglements[$i]->getEncaisse()==1)
            {
                $encaisse='Oui';
            }
            else{
                $encaisse='Non'; 
            }
           $tabReglements[$i]=' <tr class="parent" onclick="document.location = \'/Materiel/editer-materiel/'.$listReglements[$i]->getId().'\';" >
                                <td>'.$listReglements[$i]->getVisite()->getPatient()->getNom().'</td>
                                <td>'.$listReglements[$i]->getOrigine().' </td>
                                <td>'.$listReglements[$i]->getIntitule().' </td>
                                <td>'.$listReglements[$i]->getMontant().'</td>
                                <td>'.$listReglements[$i]->getModeReglement().'</td>
                                <td>'.$listReglements[$i]->getDate()->format('Y-m-d').'</td>
                                <td>'.$listReglements[$i]->getNomBanque().'</td>
                                <td>'.$listReglements[$i]->getNumCheque().'</td>
                                <td>'.$encaisse.'</td>
                                <td><a href="/Materiel/editer-materiel/'.$listReglements[$i]->getId().'"})}}><i class="fas fa-edit glyphMenu" ></i></a></td>
                                <td><a href="/Materiel/supprimer-materiel/'.$listReglements[$i]->getId().'"})}}>  <i class="fas fa-trash-alt glyphMenu"  ></i></a></td>    
                            </tr>';
        }
        
        $response = new JsonResponse;
        $response->setContent(json_encode(array(
        'listReglements' => $tabReglements)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    
    
    
    
}