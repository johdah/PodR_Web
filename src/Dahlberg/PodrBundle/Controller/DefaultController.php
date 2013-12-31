<?php

namespace Dahlberg\PodrBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DahlbergPodrBundle:Default:index.html.twig', array('name' => $name));
    }
}
