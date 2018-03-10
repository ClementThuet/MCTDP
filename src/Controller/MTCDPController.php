<?php
/**
 * Description of MTCDPController
 *
 * @author MrClément
 */
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MTCDPController extends Controller{
    
    public function index()
    {
       $number = mt_rand(0, 100);
       
        return $this->render('login.html.twig', array(
            'number' => $number,
        ));
    }
}
