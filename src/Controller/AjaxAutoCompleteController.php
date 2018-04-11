<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Materiel;
 
class AjaxAutoCompleteController extends Controller
{//public function updateDataAction(Request $request)
    public function updateDataAction(Request $request, $Entite, $champ)
    {
        //var_dump($Entite);
       //var_dump($champ);
        $data = $request->get('input');
        
        $em = $this->getDoctrine()->getManager();
 
        $query = $em->createQuery(''
                . 'SELECT c.id, c.'.$champ.' '
                . 'FROM App\Entity\\'.$Entite.' c ' 
                . 'WHERE c.'.$champ.' LIKE :data '
                . 'ORDER BY c.'.$champ.' ASC'
                )
                ->setParameter('data', '%' . $data . '%');
        $results = $query->getResult();
        
        $resultatList = '<ul id="matchList">';
        foreach ($results as $result) {
            $matchStringBold = preg_replace('/('.$data.')/i', '<strong>$1</strong>', $result[''.$champ.'']); // Replace text field input by bold one
            $resultatList .= '<li id="'.$result[''.$champ.''].'">'.$matchStringBold.'</li>'; // Create the matching list - we put maching name in the ID too
            
        }
        $resultatList .= '</ul>';
 
        $response = new JsonResponse();
        $response->setData(array('resultatList' => $resultatList));
        
        return $response;
    }
}