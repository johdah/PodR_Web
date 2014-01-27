<?php
// src/Dahlberg/PodrBundle/Controller/PlaylistController.php;
namespace Dahlberg\PodrBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PlaylistController extends Controller {
    public function indexAction() {
        return $this->render('DahlbergPodrBundle:Playlist:index.html.twig', array(
        ));
    }
}