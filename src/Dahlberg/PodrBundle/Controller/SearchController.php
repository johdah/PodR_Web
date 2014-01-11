<?php
// src/Dahlberg/PodrBundle/Controller/SearchController.php;
namespace Dahlberg\PodrBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SearchController extends Controller {
    public function indexAction() {
        return $this->render('DahlbergPodrBundle:Search:index.html.twig', array());
    }
}