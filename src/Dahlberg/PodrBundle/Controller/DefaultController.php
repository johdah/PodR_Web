<?php
// src/Dahlberg/PodrBundle/Controller/DefaultController.php;
namespace Dahlberg\PodrBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {
    public function dashboardAction() {
        $em = $this->getDoctrine()->getManager();
        $mostLikedPodcasts = $em->getRepository('DahlbergPodrBundle:UserPodcast')
            ->findMostLikedPodcast();

        return $this->render('DahlbergPodrBundle:Default:dashboard.html.twig', array(
            'mostLikedPodcasts' => $mostLikedPodcasts,
        ));
    }

    public function indexAction() {
        return $this->render('DahlbergPodrBundle:Default:index.html.twig', array());
    }
}
