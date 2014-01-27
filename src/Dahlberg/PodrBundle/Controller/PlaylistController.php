<?php
// src/Dahlberg/PodrBundle/Controller/PlaylistController.php;
namespace Dahlberg\PodrBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PlaylistController extends Controller {
    public function detailsAction($id) {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $playlist = $em->getRepository('DahlbergPodrBundle:Playlist')->findOneBy(array('id' => $id, 'owner' => $user));

        return $this->render('DahlbergPodrBundle:Playlist:details.html.twig', array(
            'playlist'  => $playlist,
        ));
    }

    public function indexAction() {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $playlists = $em->getRepository('DahlbergPodrBundle:Playlist')->findBy(
                array('owner' => $user),
                array('title' => 'ASC'));

        return $this->render('DahlbergPodrBundle:Playlist:index.html.twig', array(
            'playlists' => $playlists
        ));
    }
}