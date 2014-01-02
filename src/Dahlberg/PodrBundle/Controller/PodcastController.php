<?php

namespace Dahlberg\PodrBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PodcastController extends Controller
{
    public function indexAction()
    {
        $podcasts = $this->getDoctrine()
            ->getRepository('DahlbergPodrBundle:Podcast')
            ->findAll();

        return $this->render('DahlbergPodrBundle:Podcast:index.html.twig', array(
            'podcasts' => $podcasts,
        ));
    }
}
