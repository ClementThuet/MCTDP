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
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap4View;

class QiGongController extends Controller{

    public function menuQiGong($page,Request $request)
    {
       $page = $request->query->get('page', $page);
        
        $qb = $this->getDoctrine()
            ->getRepository(CouponQiGong::class)
            ->findAllQueryBuilder();
        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(3);
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
        
        if (null === $couponQiGong) {
            throw new NotFoundHttpException("Le coupon d'id ".$idCQG." n'existe pas.");
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
        
        $page = $request->query->get('page', $page);
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
        $listSeancesPatient = [];
        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $listSeancesPatient[] = $result;
        }

         return $this->render('QiGong/historiqueSeancesQG.html.twig', array(
          'listSeances' => $listSeancesPatient,
             'html' => $html
        ));
        
        $listSeancesPatient=$patient->getSeancesQG();
       
        
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
}
?>