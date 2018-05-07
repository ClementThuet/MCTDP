<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\CouponQiGong;
use App\Form\CouponQiGongType;
use App\Form\EditCouponQiGongType;
use App\Entity\SeanceQG;
use App\Form\EditSeanceQGType;
use App\Entity\Patient;
use App\Entity\Reglement;
use App\Form\ReglementType;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap4View;
use Symfony\Component\HttpFoundation\JsonResponse;

class QiGongController extends Controller{

    public function menuQiGong($page,Request $request)
    {
       $page = $request->query->get('page', $page);
        
        $qb = $this->getDoctrine()
            ->getRepository(CouponQiGong::class)
            ->findAllQueryBuilder();
        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(8);
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
        $listCouponsQiGong = [];
        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $listCouponsQiGong[] = $result;
        }
        
        return $this->render('QiGong/menuQiGong.html.twig',
                array('listCouponsQiGong'=>$listCouponsQiGong,
                    'html' => $html));
    }
    
    public function ajouterCouponQiGong(Request $request)
    {
        $couponQiGong = new CouponQiGong();
        $form = $this->createForm(CouponQiGongType::class, $couponQiGong);
       
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            
          
            $patientQG = $couponQiGong->getPatient();
            
            $couponQiGong->setPatient($patientQG);         
            $em = $this->getDoctrine()->getManager();
            $em->persist($couponQiGong);
            $em->flush();
            
            $repository = $this->getDoctrine()->getRepository(CouponQiGong::class);
            $listCouponsQiGong = $repository->findAll();
            
            return $this->redirectToRoute('menu_QiGong',array(
                'listCouponsQiGong'=> $listCouponsQiGong,
                'patientQG'=>$patientQG,
                'page'=>1));
        }
        return $this->render('QiGong/ajouterCouponQiGong.html.twig', array(
          'form' => $form->createView()
        ));
    }
    
    public function editerCouponQiGong($idCQG, Request $request){
        
        $em = $this->getDoctrine()->getManager();
        $couponQiGong = $em->getRepository(CouponQiGong::class)->find($idCQG);
        $patient=$couponQiGong->getPatient();
        if (null === $couponQiGong) {
            throw new NotFoundHttpException("Le  coupon d'id ".$idCQG." n'existe pas.");
        }
        
        $form = $this->get('form.factory')->create(EditCouponQiGongType::class, $couponQiGong);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
        {
            $em->flush();
            return $this->redirectToRoute('menu_QiGong',
                    array('id' => $couponQiGong->getId(),
                        'page'=>1));
        }
        return $this->render('QiGong/editerCouponQiGong.html.twig', array(
            'couponQiGong' => $couponQiGong,
            'patient'=>$patient,
            'form'   => $form->createView(),
        )); 
    }
    
    public function supprimerCouponQiGong($idCQG, Request $request ){
        
        $em = $this->getDoctrine()->getManager();
        $couponQiGong = $em->getRepository(CouponQiGong::class)->find($idCQG);
        $patientQG = $couponQiGong->getPatient();
        if (null === $couponQiGong) {
            throw new NotFoundHttpException("Le coupon d'id ".$idCQG." n'existe pas.");
        }
        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        $form = $this->get('form.factory')->create();
       
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          $em->remove($couponQiGong);
          $em->flush();
            
          $request->getSession()->getFlashBag()->add('info', "Le coupon a bien été supprimé.");

          return $this->redirectToRoute('menu_QiGong',array('page'=>1));
        }

        return $this->render('QiGong/supprimerCouponQiGong.html.twig', array(
          'couponQiGong' => $couponQiGong,
            'patient'=>$patientQG,
          'form'   => $form->createView(),
        ));
    }
    
    public function presenceQiGong($idCQG,$idPatient ){
        
        $em = $this->getDoctrine()->getManager();
        $couponQiGong = $em->getRepository(CouponQiGong::class)->find($idCQG);
        $patientQG = $couponQiGong->getPatient();
        $nbSeance=count($couponQiGong->getSeancesQG());
         if (null === $couponQiGong) {
            throw new NotFoundHttpException("Le coupon d'id ".$idCQG." n'existe pas.");
        }
        if($nbSeance>=10)
        {
            $this->addFlash("warning", "Attention, coupon déjà complet, créez en un nouveau pour ajouter une séance.");
            
            return $this->redirectToRoute('ajouter_couponQiGong');
        }
        $seanceQG = new SeanceQG();
        $date = new \DateTime;
        $seanceQG->setDate($date);
        $seanceQG->setPatient($patientQG);
        $patientQG->addSeanceQG($seanceQG);
        $couponQiGong->addSeanceQG($seanceQG);
        
        $em->persist($seanceQG);
        $em->persist($patientQG);
        $em->persist($couponQiGong);
        $em->flush();
        
        return $this->redirectToRoute('menu_QiGong',array('page'=>1));
        
     }
     
    public function historiquePresencesQiGong($idPatient, $page, Request $request ){
        
        $em = $this->getDoctrine()->getManager();
        $patient = $em->getRepository(Patient::class)->find($idPatient);
        
        $qb = $this->getDoctrine()
            ->getRepository(SeanceQG::class)
            ->findSeancesPatientQueryBuilder($idPatient);
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
        //$listSeancesPatient = [];
        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $listSeancesPatient[] = $result;
        }

         return $this->render('QiGong/historiqueSeancesQG.html.twig', array(
          'listSeances' => $listSeancesPatient,
             'html' => $html
        ));
        
        //$listSeancesPatient=$patient->getSeancesQG();
       
        
        //die(var_dump($listSeancesPatient));
         return $this->render('QiGong/historiquePresencesQG.html.twig', array(
          'listSeancesPatient' => $listSeancesPatient,
            'patient'=>$patient,
             'html' => $html
        ));
        
     }
     
    public function historiqueSeancesQG($page, Request $request){
        
        $page = $request->query->get('page', $page);
        
        $qb = $this->getDoctrine()
            ->getRepository(SeanceQG::class)
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
        $listSeances = [];
        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $listSeances[] = $result;
        }

         return $this->render('QiGong/historiqueSeancesQG.html.twig', array(
          'listSeances' => $listSeances,
             'html' => $html
        ));
        
     }
    public function editerSeanceQG($idSQG, Request $request){
        
        $em = $this->getDoctrine()->getManager();
        $seanceQG = $em->getRepository(SeanceQG::class)->find($idSQG);
        $form = $this->get('form.factory')->create(EditSeanceQGType::class, $seanceQG);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
        {
            $em->flush();
            return $this->redirectToRoute('historique_presences_QiGong',
                    array('idPatient' => $seanceQG->getPatient()->getId()));
        }
        return $this->render('QiGong/editerSeanceQG.html.twig', array(
            'idSQG' => $idSQG,
            'seanceQG'=>$seanceQG,
            'form'   => $form->createView(),
        )); 
    }
    
    public function supprimerSeanceQG($idSQG, Request $request ){
        
        $em = $this->getDoctrine()->getManager();
        $seanceQG = $em->getRepository(SeanceQG::class)->find($idSQG);
        if (null === $seanceQG) {
            throw new NotFoundHttpException("La séance d'id ".$idSQG." n'existe pas.");
        }
        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        $form = $this->get('form.factory')->create();
       
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          $patient=$seanceQG->getPatient();
          $patient->removeSeanceQG($seanceQG);
          $em->remove($seanceQG);
          $em->flush();
            
          $request->getSession()->getFlashBag()->add('info', "La séance a bien été supprimé.");

          return $this->redirectToRoute('menu_QiGong',array('page'=>1));
        }

        return $this->render('QiGong/supprimerSeanceQG.html.twig', array(
          'idSQG' => $idSQG,
           'seance' => $seanceQG,
          'form'   => $form->createView(),
        ));
    }
    
    public function reglementCouponQG(Request $request,$idCQG,$modeRegl)
    {
        $em = $this->getDoctrine()->getManager();
        $couponQG = $em->getRepository(CouponQiGong::class)->find($idCQG);
        
        $patient=$couponQG->getPatient();
        $reglement = new Reglement();
        if($modeRegl=="cheque"){$modeRegl="Chèque";} else {$modeRegl="Espèce";};
        $reglement->setModeReglement($modeRegl);
        $form = $this->createForm(ReglementType::class, $reglement);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            
            $reglement->setOrigine('Qi Gong');
            $reglement->setCouponQG($couponQG);
            $couponQG->setReglement($reglement);
            $em = $this->getDoctrine()->getManager();
            $em->persist($reglement);
            $em->persist($couponQG);
            $em->flush();
            
            return $this->redirectToRoute('menu_QiGong',array(
                'page'=> 1,
            ));
        }
        return $this->render('QiGong/reglementCouponQG.html.twig', array(
            'form' => $form->createView(),
            'patient' => $patient,
            'modeDeReglement'=>$reglement->getModeReglement(),
            '$couponQG'=>$couponQG
                 ));
     }
     
     public function filtrerCouponsQG(Request $request)
    {
        
        //Coupons complet
        if($request->get('inputComplet')=="true")
        {
            //Coupons complets et reglés
            if($request->get('inputRegler')=="true")
            {
                $em = $this->getDoctrine()->getManager();
                $qb = $em->createQueryBuilder();
                $qb->select('c')
                ->from('App\Entity\CouponQiGong', 'c')
                ->leftJoin('c.reglement', 'regl')
                ->leftJoin('c.seancesQG', 'sqg') 
                ->groupBy('c')
                ->having('COUNT(sqg.id) = 10 AND regl.id IS NOT NULL ');
            }
            //Coupons complets et non reglés
            elseif($request->get('inputNonRegler')=="true")
            {
                $em = $this->getDoctrine()->getManager();
                $qb = $em->createQueryBuilder();
                $qb->select('c')
                ->from('App\Entity\CouponQiGong', 'c')
                ->leftJoin('c.reglement', 'regl')
                ->leftJoin('c.seancesQG', 'sqg')       
                ->groupBy('c')
                ->having('COUNT(sqg.id) = 10 AND regl.id IS NULL');
            }
            //Coupons complets
            else{
                $em = $this->getDoctrine()->getManager();
                $qb = $em->createQueryBuilder();
                $qb->select('c')
                ->from('App\Entity\CouponQiGong', 'c')
                ->leftJoin('c.seancesQG', 'sqg') 
                ->groupBy('c')
                ->having('COUNT(sqg.id) = 10 ');
            }
        }
        if($request->get('inputIncomplet') == "true")
        {
           //coupons non complet et réglés
           if($request->get('inputRegler')=="true")
           {
               $em = $this->getDoctrine()->getManager();
               $qb = $em->createQueryBuilder();
               $qb->select('c')
               ->from('App\Entity\CouponQiGong', 'c')
               ->leftJoin('c.reglement', 'regl')
               ->leftJoin('c.seancesQG', 'sqg')   
               ->groupBy('c')
               ->having('COUNT(sqg.id) != 10 AND regl.id IS NOT NULL ');
           }
           //coupons non complets et non reglés
           elseif($request->get('inputNonRegler')=="true")
           {
               $em = $this->getDoctrine()->getManager();
               $qb = $em->createQueryBuilder();
               $qb->select('c')
               ->from('App\Entity\CouponQiGong', 'c')
               ->leftJoin('c.reglement', 'regl')
               ->leftJoin('c.seancesQG', 'sqg')   
               ->groupBy('c')
               ->having('COUNT(sqg.id) != 10 AND regl.id IS NULL ');
               $classTr=" class=''";
           }
            //Coupons non complets
            else
            {
                $em = $this->getDoctrine()->getManager();
                $qb = $em->createQueryBuilder();
                $qb->select('c')
                ->from('App\Entity\CouponQiGong', 'c')
                ->leftJoin('c.seancesQG', 'sqg') 
                ->groupBy('c')
                ->having('COUNT(sqg.id) != 10 ');
            }
        }
        //Coupon réglés
        if($request->get('inputRegler') == "true" && $request->get('inputComplet')=="false" && $request->get('inputIncomplet')=="false")
        {
            $em = $this->getDoctrine()->getManager();
               $qb = $em->createQueryBuilder();
               $qb->select('c')
               ->from('App\Entity\CouponQiGong', 'c')
               ->leftJoin('c.reglement', 'regl')
               ->groupBy('c')
               ->having('regl.id IS NOT NULL ');
        }
        //Coupon non réglés
        if($request->get('inputNonRegler') == "true" && $request->get('inputComplet')=="false" && $request->get('inputIncomplet')=="false")
        {
            $em = $this->getDoctrine()->getManager();
               $qb = $em->createQueryBuilder();
               $qb->select('c')
               ->from('App\Entity\CouponQiGong', 'c')
               ->leftJoin('c.reglement', 'regl')
               ->groupBy('c')
               ->having('regl.id IS  NULL ');
        }
        //Si C non complet et non reglé => gris
        //Si C complet et reglé =>vert
        // SI C non complet et reglé =>vert
        //Si Ccomplet et non reglé =>rouge
        $query = $qb->getQuery();
        $listCoupons = $query->getResult();
        $tabCoupons=[];
        $classTr=" class=''";
        for($i=0;$i<count($listCoupons);$i++)
        {
           /* if( isset($classTr) ===false)
            {
                if($listCoupons[$i]->getReglement() !==null )
                {
                    $classTr=" class='reglEffectue'";
                }
                else
                {
                    $classTr=" class='reglEnAttente'";
                }
            }*/
            
            $tabCoupons[$i]=' <tr'.$classTr.' >
                                <td>'.$listCoupons[$i]->getId().'</td>
                                <td>'.$listCoupons[$i]->getPatient()->getNom().'</td>
                                <td>'.$listCoupons[$i]->getPatient()->getPrenom().' </td>
                                <td>'.count($listCoupons[$i]->getSeancesQG()).' </td>
                                <td>'.$listCoupons[$i]->getObservations().'</td>
                                <td> <a href="/QiGong/presence-QiGong/'.$listCoupons[$i]->getId().'/'.$listCoupons[$i]->getPatient()->getId().'"><i class="fas fa-user glyphMenu"></i></a></td>
                                <td> 
                                    <div class="divReglement"  >
                                        <a href="/QiGong/reglement-coupon-QiGong/'.$listCoupons[$i]->getId().'/cheque" ><img src="/assets/img/icone_cheque.png" style="width:40px;">  </a> 
                                        <a href="/QiGong/reglement-coupon-QiGong/'.$listCoupons[$i]->getId().'/espece" ><img src="/assets/img/icone_espece.png" style="width:40px;">  </a>
                                    </div style="display:none;><i class="fas fa-euro-sign glyphMenu btReglement"></i>
                                </td> 
                                <td><a href="/QiGong/historique-presences-QiGong/'.$listCoupons[$i]->getPatient()->getId().'/page-1"><i class="fas fa-archive glyphMenu"></i></a></td>
                                <td><a href="/QiGong/editer-couponQiGong/'.$listCoupons[$i]->getId().'"><i class="fas fa-edit glyphMenu"></i></a></td>    
                                <td><a href="/QiGong/supprimer-couponQiGong/'.$listCoupons[$i]->getId().'"><i class="fas fa-trash-alt glyphMenu"></i></a></td>
                            </tr>';
        }
        
        $response = new JsonResponse;
        $response->setContent(json_encode(array(
        'listCouponsQG' => $tabCoupons,
        'filtreEffectuee'=>1)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}