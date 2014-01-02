<?php
// src/Dahlberg/PodrBundle/Controller/EpisodeController.php;
namespace Dahlberg\PodrBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EpisodeController extends Controller {
    public function detailsAction($id) {
        $episode = $this->getDoctrine()
            ->getRepository('DahlbergPodrBundle:Episode')
            ->find($id);

        if(!$episode)
            throw $this->createNotFoundException('The episode does not exist');

        return $this->render('DahlbergPodrBundle:Episode:details.html.twig', array(
            'episode' => $episode,
        ));
    }

}