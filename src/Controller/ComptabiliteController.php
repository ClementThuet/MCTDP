<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Reglement;
use App\Entity\Visite;
use App\Form\EditReglementType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Amenadiel\JpGraph\Graph\Graph;
use Amenadiel\JpGraph\Themes\UniversalTheme;
use Amenadiel\JpGraph\Plot\LinePlot;
use Amenadiel\JpGraph\Graph\PieGraph;
use Amenadiel\JpGraph\Plot\PiePlot;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap4View;

class ComptabiliteController extends Controller{
    
    public function menuComptabilite()
    {
        return $this->render('Comptabilite/menuComptabilite.html.twig');
    }
    
    public function menuReglements($page,Request $request)
    {
        $request->getSession()->remove('urlRetourFicheRegl');
        $qb = $this->getDoctrine()
            ->getRepository(Reglement::class)
            ->findAllQueryBuilder();
        
        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(5);
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
        $listReglements = [];
        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $listReglements[] = $result;
        }
        
        return $this->render('Comptabilite/menuReglements.html.twig', array(
            'listReglements'=>$listReglements,
            'html'=>$html,
        ));
    }
    
    public function ficheReglement($idReglement,Request $request){
        
        $em = $this->getDoctrine()->getManager();
        $reglement = $em->getRepository(Reglement::class)->find($idReglement);
        $urlRetourFicheRegl=$request->getSession()->get('urlRetourFicheRegl');
        $urlRetourFicheReglId=$request->getSession()->get('urlRetourFicheReglId');
        //Détermination de la redirection
        if ($urlRetourFicheRegl =="fiche_visite")
        {
            $retourPath='fiche_visite';
            $retourID=$urlRetourFicheReglId;
        }
        elseif ($urlRetourFicheRegl =="menu_QiGong"){
            $retourPath='menu_QiGong';
            $retourID='';
        }
        else
        {
            $retourPath='';
            $retourID='';
        }
        //Obtention du patient et de la visite selon si visieteo u couponQiGong
        if($reglement->getVisite() != null)
        {
            $patient=$reglement->getVisite()->getPatient();
            $visite=$reglement->getVisite();
        }
        else
        {
            $patient=$reglement->getCouponQG()->getPatient();
            $visite=$reglement->getCouponQG();
        }
        
        return $this->render('Comptabilite/ficheReglement.html.twig', array(
            'reglement' => $reglement,
            'visite'   => $visite,
            'patient'   => $patient,
            'retourPath'=>$retourPath,
            'retourID'=>$retourID
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
           //Obtention du patient et de la visite selon si visieteo u couponQiGong
            if($reglement->getVisite() != null)
            {
                $visite=$reglement->getVisite();
                $visite->removeReglement($reglement);
                $em->persist($visite);
            }
            else
            {
                $couponQG=$reglement->getCouponQG();
                $couponQG->setReglement(null);
                $em->persist($couponQG);
            }
          
          $em->remove($reglement);
          $em->flush();
            
          $request->getSession()->getFlashBag()->add('info', "Le réglement a bien été supprimé.");

          return $this->redirectToRoute('menu_reglements', array('page'=>1));
        }
        //Obtention du patient et de la visite selon si visieteo u couponQiGong
        if($reglement->getVisite() != null)
        {
            $patient=$reglement->getVisite()->getPatient();
            $visite=$reglement->getVisite();
        }
        else
        {
            $patient=$reglement->getCouponQG()->getPatient();
            $visite=$reglement->getCouponQG();
        }
        return $this->render('Comptabilite/supprimerReglement.html.twig', array(
           'reglement' => $reglement,
           'visite'   => $visite,
           'patient'   => $patient,
           'form'   => $form->createView(),
        ));
    }
    
    //A supprimer
    public function filtrerReglements(Request $request)
    {
        
       /* if($request->get('inputNnEncaisse') == "false")
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
            if($listReglements[$i]->getVisite()->getPatient()->getNom() ==null)
            {
                $patientNoms=$listReglements[$i]->getCouponQG()->getPatient()->getNom()." ".$patientNoms=$listReglements[$i]->getCouponQG()->getPatient()->getPrenom();
            }
            else
            {
                $patientNoms=$listReglements[$i]->getVisite()->getPatient()->getNom()." ".$patientNoms=$listReglements[$i]->getVisite()->getPatient()->getPrenom();
            }
           $tabReglements[$i]=' <tr class="parent" onclick="document.location = \'/Materiel/editer-materiel/'.$listReglements[$i]->getId().'\';" >
                                <td>'.$patientNoms.'</td>
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
*/
        return $response;
    }
    
    public function menuStatistiques()
    {
        return $this->render('Comptabilite/menuStatistiques.html.twig'); 
    }
    public function statistiquesCA($origine,$critere,$valeur)
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
                $qb = $em->getRepository(Reglement::class)->findYearByMonth($origine,$valeur,$mois);
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
                $qb = $em->getRepository(Reglement::class)->findMonthByDay($origine,$annee,$mois,$jour);
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
        $graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,11);
        
        
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
        $graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD,11);

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
    
    public function statistiquesCADates($origine,$date1,$date2)
    {
        $em = $this->getDoctrine()->getManager();
        
        $sommesRegl=[];
        $datesX=[];
        $sdate    = strtotime($date1);
        $edate    = strtotime($date2);

        $dates = array();

        for($i = $sdate; $i <= $edate; $i += strtotime('+1 day', 0))
        {
            $dates[] = date('Y/m/d', $i);
        }
        for($j=0;$j<count($dates);$j++)
        {
            $qb = $em->getRepository(Reglement::class)->findByDate($origine,$dates[$j]);
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
        $graph->title->Set('Chiffre d\'affaire du '.$valeur.'');
        $graph->SetMargin(70,70,40,70);
        $graph->title->SetFont(FF_ARIAL,FS_NORMAL,18);
        $graph->title->SetMargin(10);

        $graph->yaxis->HideTicks(false,false);
        $graph->yaxis->title->Set('Chiffre d\'affaire (€)');
        $graph->yaxis->title->SetMargin(20);
        $graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,11);
        
        $graph->xgrid->Show();
        $graph->xgrid->SetLineStyle("solid");
        $graph->xaxis->SetTickLabels($datesX);
        $graph->xaxis->SetLabelAngle(50);
        
        $graph->xgrid->SetColor('#E3E3E3');
        $graph->xaxis->title->Set('Temps');
        $graph->xaxis->title->SetMargin(20);
        $graph->yaxis->SetLabelAlign('center','bottom'); 
        $graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD,11);

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
    
    public function statistiquesVisites($critere,$valeur)
    {
        if($valeur=="current")
        {
            $valeur=date("Y");
        }
        $em = $this->getDoctrine()->getManager();
        if($critere=="annee")
        {
            $sommesVisites=[];
            //Parcours des 12 mois
            for($mois=1;$mois<=12;$mois++)
            {
                $sommesVisitesDuMois=0;
                $qb = $em->getRepository(Visite::class)->findVisitesParMois($valeur,$mois);
                $query = $qb->getQuery();
                $visitesMois = $query->getResult();

                //Parcours des réglements du mois
                for($i=0;$i<count($visitesMois);$i++)
                {
                    $sommesVisitesDuMois+=count($visitesMois[$i]);
                }
                array_push($sommesVisites,$sommesVisitesDuMois);
            }
        }
        if($critere=="mois")
        {
            //Division de l'url en 2 blocks : l'année et le mois
            $criteres= explode('-', $valeur);
            $mois=$criteres[0];
            $annee=$criteres[1];
            $sommesVisites=[];
            //Parcours des jours
            for($jour=1;$jour<=cal_days_in_month(CAL_GREGORIAN,$mois,$annee);$jour++)
            {
                $sommesVisitesDuJour=0;
                $qb = $em->getRepository(Visite::class)->findVisitesParJour($annee,$mois,$jour);
                $query = $qb->getQuery();
                $visitesJour = $query->getResult();

                //Parcours des réglements du mois
                for($i=0;$i<count($visitesJour);$i++)
                {
                    $sommesVisitesDuJour+=count($visitesJour[$i]);
                }
                array_push($sommesVisites,$sommesVisitesDuJour);
            }
        }
        
         // Setup the graph
        $graph = new Graph(1000,600);
        $graph->SetMargin(70,70,40,70);
        $graph->SetScale('textlin');
        $theme_class=new UniversalTheme;
        $graph->SetTheme($theme_class);
        
        $graph->title->SetFont(FF_ARIAL,FS_NORMAL,18);
        $graph->title->SetMargin(10);

        $graph->yaxis->title->Set('Nombre de visite');
        $graph->yaxis->title->SetMargin(20);
        $graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,11);
        
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
            $graph->title->Set('Nombre de visite en '.$mois.'');
        }
        //setlocale(LC_TIME, 'French_France');                                              
       // $mois = strftime('%B', mktime(0, 0, 0, $mois));
        
        $graph->xgrid->SetColor('#E3E3E3');
        $graph->xaxis->title->Set('Temps');
        $graph->xaxis->title->SetMargin(20);
        $graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD,11);

        // Create the first line
        $p1 = new LinePlot($sommesVisites);
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
        $redirect = $this->render('Comptabilite/statistiquesVisites.html.twig', array(
                                      'EncodedImage' => $image,                   
                                       ));  
        return $redirect; 
    }
    
    public function statistiquesVisitesDates($date1,$date2)
    {
        $em = $this->getDoctrine()->getManager();
        
        $sommesVisites=[];
        $datesX=[];
        $sdate    = strtotime($date1);
        $edate    = strtotime($date2);

        $dates = array();

        for($i = $sdate; $i <= $edate; $i += strtotime('+1 day', 0))
        {
            $dates[] = date('Y/m/d', $i);
        }
        for($j=0;$j<count($dates);$j++)
        {
            $qb = $em->getRepository(Visite::class)->findByDate($dates[$j]);
            $query = $qb->getQuery();
            $visitesJour = $query->getResult();
            $sommesVisitesDuJour=0;
            for($k=0;$k<count($visitesJour);$k++)
            {
                $sommesVisitesDuJour+=count($visitesJour[$k]);
            }

            array_push($sommesVisites,$sommesVisitesDuJour);
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
        $graph->SetScale('textlin');
        $theme_class=new UniversalTheme;
        $graph->SetTheme($theme_class);
        $graph->title->Set('Visites du '.$valeur.'');
        $graph->SetMargin(70,70,40,70);
        $graph->title->SetFont(FF_ARIAL,FS_NORMAL,18);
        $graph->title->SetMargin(10);

        $graph->yaxis->HideTicks(false,false);
        $graph->yaxis->title->Set('Nombre de visites');
        $graph->yaxis->title->SetMargin(20);
        $graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,11);
        
        $graph->xgrid->Show();
        $graph->xgrid->SetLineStyle("solid");
        $graph->xaxis->SetTickLabels($datesX);
        $graph->xaxis->SetLabelAngle(50);
        
        $graph->xgrid->SetColor('#E3E3E3');
        $graph->xaxis->title->SetMargin(20);
        $graph->yaxis->SetLabelAlign('center','bottom'); 
        $graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD,11);

        // Create the first line
        $p1 = new LinePlot($sommesVisites);
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
    public function statistiquesMDR()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository(Reglement::class)->findByMDR('Chèque');
        $query = $qb->getQuery();
        $nbReglCheque = $query->getResult();
        
        $qb = $em->getRepository(Reglement::class)->findByMDR('Espèces');
        $query = $qb->getQuery();
        $nbReglEspece = $query->getResult();
        
        $nbTotalRegl=count($nbReglCheque)+count($nbReglEspece);
        $pourcentageReglCheque=count($nbReglCheque)*100/$nbTotalRegl;
        $pourcentageReglEspece=count($nbReglEspece)*100/$nbTotalRegl;
        $data = array($pourcentageReglCheque,$pourcentageReglEspece);
 
        // A new pie graph
        $graph = new PieGraph(600,600);
        $graph->SetShadow();

        // Title setup
       // $graph->title->Set("Repartition des modes de reglements");
        $graph->title->SetFont(FF_ARIAL,FS_NORMAL,18);
        $graph->title->SetMargin(30);
        $graph->legend->SetPos(0.5,0.95,'center','bottom');
        // Setup the pie plot
        $p1 = new PiePlot($data);

        // Adjust size and position of plot
        $p1->SetSize(0.35);
        $p1->SetCenter(0.5,0.52);
        
        $p1->SetSliceColors(array('#3593ca','#83837c'));
        // Setup slice labels and move them into the plot
        $p1->value->SetFont(FF_FONT1,FS_BOLD);
        $p1->value->SetColor("black");
        $p1->SetLabelPos(0.65);
        $legends = array('Espèces (%d)','Chèque (%d)');  
        $p1->SetLegends($legends); 
        

        // Explode all slices
        $p1->ExplodeAll(10);

        // Add drop shadow
        $p1->SetShadow();

        // Finally add the plot
        $graph->Add($p1);
        
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
        
        $redirect = $this->render('Comptabilite/statistiquesMDR.html.twig', array(
                                      'EncodedImage' => $image,                   
                                       ));  
        
        return $redirect;  
    }
    public function rechercherReglement($Entite, $champ, $valeur)
    {
        if($champ == "nomPatient")
        {
            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder();
            $qb 
            ->select('pat')
            ->from('App\Entity\Patient', 'pat')        
            ->where('pat.nom LIKE :valeur')
            ->orWhere('pat.prenom LIKE :valeur')
            ->orderBy('pat.nom', 'ASC')
            ->setParameter('valeur', '%'.$valeur.'%');
            $query = $qb->getQuery();
            $listPats = $query->getResult();
            $ids=[];
            foreach($listPats as $patient){
                array_push($ids, $patient->getId());
            }
            if (isset($ids))
            {
                $listReglements=[];
                for($i=0;$i<count($ids);$i++)
                {
                    //Recherche pour origine  = visites (qb2) et Qi GOng (qb3)
                    $em2 = $this->getDoctrine()->getManager();
                    $qb2 = $em2->createQueryBuilder();
                    $qb2 
                    ->select('r')
                    ->from('App\Entity\Reglement', 'r')  
                    ->innerJoin('r.visite', 'visit')
                    ->innerJoin('visit.patient', 'pat')
                    ->where('pat.id = :patID')
                    ->setParameter('patID', ''.$ids[$i].'');
                    $query2 = $qb2 ->getQuery();

                    $em3 = $this->getDoctrine()->getManager();
                    $qb3 = $em3->createQueryBuilder();
                    $qb3 
                    ->select('r')
                    ->from('App\Entity\Reglement', 'r')  
                    ->innerJoin('r.couponQG', 'cQG')
                    ->innerJoin('cQG.patient', 'patQG')
                    ->where('patQG.id = :patID')       
                    ->setParameter('patID', ''.$ids[$i].'');
                    $query3 = $qb3 ->getQuery();
                    $elemRegl=array_merge($query2->getResult(),$query3->getResult());
                    $listReglements=array_merge($listReglements,$elemRegl);
                }
            }
            else{
                $listReglements='';
            }
        }
        else
        {
            if($champ == "date")
            {
                if(strtotime($valeur)==true)
                {
                    $valeur=date("Y-m-d", strtotime($valeur));
                }
            }
            if($champ == "encaisse")
            {
                
                if($valeur=="Non" || $valeur=="non")
                {
                    $valeur=0;
                }
                elseif($valeur=="Oui" || $valeur=="oui")
                {
                    $valeur=1;
                }
            }
            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder();
            $qb->select('m ')
            ->from('App\Entity\\'.$Entite.'', 'm')
            ->where('m.'.$champ.' LIKE :valeur ')
            ->orderBy('m.date', 'DESC') 
            ->setParameter('valeur', '%'.$valeur.'%');
             $query = $qb->getQuery();
             $listReglements = $query->getResult();
        }
        return $this->render('Comptabilite/menuReglements.html.twig', array(
            'listReglements'=>$listReglements,
            'rechercheEffectuee'=>1,
        ));
    }
    
    public function rechercherReglementDates($origine,$date1,$date2)
    {
        $em = $this->getDoctrine()->getManager();
        
        $listReglements=[];
        $dates = [];
        $sdate    = strtotime($date1);
        $edate    = strtotime($date2);

        for($i = $sdate; $i <= $edate; $i += strtotime('+1 day', 0))
        {
            $dates[] = date('Y/m/d', $i);
        }
        //Pour chaque jour 
        for($j=0;$j<count($dates);$j++)
        {
            $qb = $em->getRepository(Reglement::class)->findByDate($origine,$dates[$j]);
            $query = $qb->getQuery();
            $reglementsJour = $query->getResult();
            //Pour chaque reglement de ce jour
            for($k=0;$k<count($reglementsJour);$k++)
            {
                array_push($listReglements,$reglementsJour[$k]);
            }
        }
        return $this->render('Comptabilite/menuReglements.html.twig', array(
            'listReglements'=>$listReglements,
            'origine'=>$origine,
            'date1'=>$date1,
            'date2'=>$date2,
            'rechercheEffectuee'=>1,
        ));
    }
}