<?php
// src/Dahlberg/PodrBundle/Controller/APIV1EpisodeController.php;
namespace Dahlberg\PodrBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class APIV1EpisodeController extends FOSRestController {
    public function getEpisodeAction($id) {
        return $this->getDoctrine()
            ->getRepository('DahlbergPodrBundle:Episode')
            ->find($id);
    }
}