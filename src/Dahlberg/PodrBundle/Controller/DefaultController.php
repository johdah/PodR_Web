<?php
// src/Dahlberg/PodrBundle/Controller/DefaultController.php;
namespace Dahlberg\PodrBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DahlbergPodrBundle:Default:index.html.twig', array());
    }
}
