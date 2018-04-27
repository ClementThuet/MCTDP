<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Materiel;
 
class AjaxAutoCompleteController extends Controller
{
    public function updateDataAction(Request $request, $Entite, $champ)
    {
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
        
        $resultatList = '<ul class="zoneAffichageRecherche" id="matchList">';
        $i=0;
        foreach ($results as $result) {
            if($i<3){
                $matchStringBold = preg_replace('/('.$data.')/i', '$1', $result[''.$champ.'']); // Replace text field input by bold one
                $resultatList .= '<li id="'.$result[''.$champ.''].'">'.$matchStringBold.'</li>'; // Create the matching list - we put maching name in the ID too
                $i++;
            }
        }
            
        $resultatList .= '</ul>';
 
        $response = new JsonResponse();
        $response->setData(array('resultatList' => $resultatList));
        
        return $response;
    }
}