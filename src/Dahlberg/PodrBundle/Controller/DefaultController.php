<?php
// src/Dahlberg/PodrBundle/Controller/DefaultController.php;
namespace Dahlberg\PodrBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {
    public function dashboardAction() {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $latestUnreadEpisodes = $em->getRepository('DahlbergPodrBundle:UserEpisode')
            ->findLatestUnreadEpisodes($user);
        $mostLikedPodcasts = $em->getRepository('DahlbergPodrBundle:UserEpisode')
            ->findMostLikedPodcasts($user);
        $startedEpisodes = $em->getRepository('DahlbergPodrBundle:UserEpisode')
            ->findStartedEpisodes($user);
        $mostUnarchivedPodcasts = $em->getRepository('DahlbergPodrBundle:UserEpisode')
            ->findMostUnarchivedPodcasts($user);
        $mostUnreadPodcasts = $em->getRepository('DahlbergPodrBundle:UserEpisode')
            ->findMostUnreadPodcasts($user);
        $oldestUnarchivedEpisodes = $em->getRepository('DahlbergPodrBundle:UserEpisode')
            ->findOldestUnarchivedEpisodes($user);

        return $this->render('DahlbergPodrBundle:Default:dashboard.html.twig', array(
            'latestUnreadEpisodes'      => $latestUnreadEpisodes,
            'mostLikedPodcasts'         => $mostLikedPodcasts,
            'mostUnarchivedPodcasts'    => $mostUnarchivedPodcasts,
            'mostUnreadPodcasts'        => $mostUnreadPodcasts,
            'oldestUnarchivedEpisodes'  => $oldestUnarchivedEpisodes,
            'startedEpisodes'           => $startedEpisodes,
        ));
    }

    public function indexAction() {
        return $this->render('DahlbergPodrBundle:Default:index.html.twig', array());
    }
}
