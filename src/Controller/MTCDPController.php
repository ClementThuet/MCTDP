<?php
/**
 * Description of MTCDPController
 *
 * @author MrClément
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MTCDPController extends Controller{
    
    public function index()
    {
        return $this->render('login.html.twig');
    }
   
    public function menu()
    {
        return $this->render('home.html.twig');
    }
}
