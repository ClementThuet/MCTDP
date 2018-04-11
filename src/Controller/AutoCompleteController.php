<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AutoCompleteController extends Controller
{
    public function indexAction()
    {
        /* Semble inutile
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('App\Repository\MaterielsRepository')
          ;
 
        $listResultats = $repository->findBy(
            array('id' => '1'),                      // Critere
            array('id' => 'desc'),        // Tri
            10,                         // Limite
            null                          // Offset
          );
 
        
      return $this->render('Materiel/menuMateriels.html.twig', array(
          'listResultats' => $listResultats,
      ));*/
      
    }
}