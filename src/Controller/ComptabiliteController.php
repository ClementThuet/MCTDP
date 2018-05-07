<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Reglement;
use App\Form\EditReglementType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Amenadiel\JpGraph\Graph\Graph;
use Amenadiel\JpGraph\Themes\UniversalTheme;
use Amenadiel\JpGraph\Plot\LinePlot;

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
        
        if($request->get('inputNnEncaisse') == "false")
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
    
    public function menuStatistiques()
    {
        return $this->render('Comptabilite/menuStatistiques.html.twig'); 
    }
    public function statistiquesCA($critere,$valeur)
    {
        if($valeur=="current")
        {
            $valeur=date("Y");
        }
        
        $em = $this->getDoctrine()->getManager();
        $sommesRegl=[];
        if($critere=="annee")
        {
            //Parcours des 12 mois
            for($mois=1;$mois<=12;$mois++)
            {
                $sommesReglDuMois=0;
                $qb = $em->getRepository(Reglement::class)->findYearByMonth($valeur,$mois);
                $query = $qb->getQuery();
                $reglementsMois = $query->getResult();

                //Parcours des réglements du mois
                for($i=0;$i<count($reglementsMois);$i++)
                {
                    $sommesReglDuMois+=$reglementsMois[$i]->getMontant();
                }
                array_push($sommesRegl,$sommesReglDuMois);
            }
        }
        if($critere=="mois")
        {
            //Division de l'url en 2 blocks : l'année et le mois
            $criteres= explode('-', $valeur);
            $mois=$criteres[0];
            $annee=$criteres[1];
            //Parcours des jours
            for($jour=1;$jour<=cal_days_in_month(CAL_GREGORIAN,$mois,$annee);$jour++)
            {
                $sommesReglDuJour=0;
                $qb = $em->getRepository(Reglement::class)->findMonthByDay($annee,$mois,$jour);
                $query = $qb->getQuery();
                $reglementsJour = $query->getResult();

                //Parcours des réglements du mois
                for($i=0;$i<count($reglementsJour);$i++)
                {
                    $sommesReglDuJour+=$reglementsJour[$i]->getMontant();
                }
                array_push($sommesRegl,$sommesReglDuJour);
            }
        }

        // Setup the graph
        $graph = new Graph(1000,600);
        $graph->SetScale('textint');
        $theme_class=new UniversalTheme;
        $graph->SetTheme($theme_class);
        $graph->title->Set('Chiffre d\'affaire '.$valeur.'');
        $graph->SetMargin(70,70,40,70);
        $graph->title->SetFont(FF_ARIAL,FS_NORMAL,18);
        $graph->title->SetMargin(10);
       

        $graph->yaxis->HideTicks(false,false);
        $graph->yaxis->title->Set('Chiffre d\'affaire (€)');
        $graph->yaxis->title->SetMargin(20);
        $graph->yaxis->title->SetFont(FF_ARIAL,FS_NORMAL,13);
        
        $graph->xgrid->Show();
        $graph->xgrid->SetLineStyle("solid");
        if($critere=="annee")
        {
            $graph->xaxis->SetTickLabels(array('Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'));
        }
        if($critere=="mois")
        {
            $jours=[];
            for($jour=1;$jour<=cal_days_in_month(CAL_GREGORIAN,$mois,$annee);$jour++)
            {
                array_push($jours,$jour);
            }
            $graph->xaxis->SetTickLabels($jours);
            setlocale(LC_TIME, 'French_France');                                              
            $mois = strftime('%B', mktime(0, 0, 0, $mois));
            $graph->title->Set('Chiffre d\'affaire '.$mois.'');
        }
        $graph->xgrid->SetColor('#E3E3E3');
        $graph->xaxis->title->Set('Temps');
        $graph->xaxis->title->SetMargin(20);
        $graph->xaxis->title->SetFont(FF_ARIAL,FS_NORMAL,13);

        // Create the first line
        $p1 = new LinePlot($sommesRegl);
        $graph->Add($p1);
        $p1->SetColor("#6495ED");
        $graph->legend->SetFrameWeight(1);
        $gdImgHandler = $graph->Stroke(_IMG_HANDLER);
        //Start buffering
        ob_start();      
        //Print the data stream to the buffer
        $graph->img->Stream(); 
        //Get the conents of the buffer
        $image_data = ob_get_contents();
        //Stop the buffer/clear it.
        ob_end_clean();
        //Set the variable equal to the base 64 encoded value of the stream.
        //This gets passed to the browser and displayed.
        $image = base64_encode($image_data);
        $redirect = $this->render('Comptabilite/statistiquesCA.html.twig', array(
                                      'EncodedImage' => $image,                   
                                       ));  
        return $redirect;  
    }
    
    public function statistiquesCADates($date1,$date2)
    {
        $em = $this->getDoctrine()->getManager();
        $tabDate1= explode('-', $date1);
        $tabDate2= explode('-', $date2);
        $jourDate1=$tabDate1[0];
        $moisDate1=$tabDate1[1];
        $anneeDate1=$tabDate1[2];
        
        $jourDate2=$tabDate2[0];
        $moisDate2=$tabDate2[1];
        $anneeDate2=$tabDate2[2];
        $sommesRegl=[];
        $datesX=[];
        
       /* if($date1 > $date2)
        {
            return false;
        }    
*/
        $sdate    = strtotime($date1);
        $edate    = strtotime($date2);

        $dates = array();

        for($i = $sdate; $i <= $edate; $i += strtotime('+1 day', 0))
        {
            $dates[] = date('Y/m/d', $i);
        }
        for($j=0;$j<count($dates);$j++)
        {
            $qb = $em->getRepository(Reglement::class)->findByDate($dates[$j]);
            $query = $qb->getQuery();
            $reglementsJour = $query->getResult();
            $sommesReglDuJour=0;
            for($k=0;$k<count($reglementsJour);$k++)
            {
                $sommesReglDuJour+=$reglementsJour[$k]->getMontant();
            }

            array_push($sommesRegl,$sommesReglDuJour);
            if(count($dates)>60)
            {
                //var_dump(date_create($dates[$j])->format('d'));
                if(date_create($dates[$j])->format('d')=='01')
                {
                    array_push($datesX,$dates[$j]);
                }
                elseif(date_create($dates[$j])->format('d')=='15')
                {
                    array_push($datesX,$dates[$j]);
                }
                else
                {
                    array_push($datesX,'');
                }
            }
            else
            {
                array_push($datesX,$dates[$j]);
            }
        }
        $valeur=$date1.' au '.$date2;
       
        // Setup the graph
        $graph = new Graph(1000,600);
        $graph->SetScale('textint');
        $theme_class=new UniversalTheme;
        $graph->SetTheme($theme_class);
        $graph->title->Set('Chiffre d\'affaire '.$valeur.'');
        $graph->SetMargin(70,70,40,70);
        $graph->title->SetFont(FF_ARIAL,FS_NORMAL,18);
        $graph->title->SetMargin(10);

        $graph->yaxis->HideTicks(false,false);
        $graph->yaxis->title->Set('Chiffre d\'affaire (€)');
        $graph->yaxis->title->SetMargin(20);
        $graph->yaxis->title->SetFont(FF_ARIAL,FS_NORMAL,13);
        
        $graph->xgrid->Show();
        $graph->xgrid->SetLineStyle("solid");
        $graph->xaxis->SetTickLabels($datesX);
        $graph->xaxis->SetLabelAngle(60);
        
        $graph->xgrid->SetColor('#E3E3E3');
        $graph->xaxis->title->Set('Temps');
        $graph->xaxis->title->SetMargin(20);
        $graph->yaxis->SetLabelAlign('center','bottom'); 
        $graph->xaxis->title->SetFont(FF_ARIAL,FS_NORMAL,13);

        // Create the first line
        $p1 = new LinePlot($sommesRegl);
        $graph->Add($p1);
        $p1->SetColor("#6495ED");
        $graph->legend->SetFrameWeight(1);
        $gdImgHandler = $graph->Stroke(_IMG_HANDLER);
        //Start buffering
        ob_start();      
        //Print the data stream to the buffer
        $graph->img->Stream(); 
        //Get the conents of the buffer
        $image_data = ob_get_contents();
        //Stop the buffer/clear it.
        ob_end_clean();
        //Set the variable equal to the base 64 encoded value of the stream.
        //This gets passed to the browser and displayed.
        $image = base64_encode($image_data);
        $redirect = $this->render('Comptabilite/statistiquesCA.html.twig', array(
                                      'EncodedImage' => $image,                   
                                       ));  
        return $redirect;  
    }      
    
}