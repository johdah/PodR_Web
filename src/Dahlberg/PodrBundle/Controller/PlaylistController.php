<?php
// src/Dahlberg/PodrBundle/Controller/PlaylistController.php;
namespace Dahlberg\PodrBundle\Controller;

use Dahlberg\PodrBundle\Entity\Playlist;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class PlaylistController extends Controller {
    public function detailsAction($id) {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $playlist = $em->getRepository('DahlbergPodrBundle:Playlist')->findOneBy(array('id' => $id, 'owner' => $user));
        $episodes = $em->getRepository('DahlbergPodrBundle:UserEpisode')->findUnarchivedEpisodesByPlaylist($user, $playlist);

        return $this->render('DahlbergPodrBundle:Playlist:details.html.twig', array(
            'episodes'  => $episodes,
            'playlist'  => $playlist,
        ));
    }

    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $form = $this->createFormBuilder()
            ->add('title', 'text', array(
                'required' => true
            ))
            ->add('save', 'submit')
            ->getForm();
        $form->handleRequest($request);
        $formSuccess = null;

        if ($form->isValid()) {
            $playlist = new Playlist();
            $playlist->setOwner($user);
            $playlist->setTitle($form->get('title')->getData());

            $em->persist($playlist);
            try {
                $em->flush();
                $formSuccess = "Podcast added!";
            } catch(DBALException $e) {
                $form->addError(new FormError("Can't add that podcast. Maybe it already exists"));
            }
        }

        $playlists = $em->getRepository('DahlbergPodrBundle:Playlist')->findBy(
                array('owner' => $user),
                array('title' => 'ASC'));

        return $this->render('DahlbergPodrBundle:Playlist:index.html.twig', array(
            'form'      => $form->createView(),
            'formSuccess' => $formSuccess,
            'playlists' => $playlists
        ));
    }

    public function removePodcastAction($playlistId, $podcastId) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $playlist = $em->getRepository('DahlbergPodrBundle:Playlist')->findOneBy(array('id' => $playlistId, 'owner' => $user));
        $podcast = $em->getRepository('DahlbergPodrBundle:Podcast')->findOneBy(array('id' => $podcastId));

        $playlistPodcast = $this->getDoctrine()->getRepository('DahlbergPodrBundle:PlaylistPodcast')
            ->findOneBy(array('playlist' => $playlist, 'podcast' => $podcast));

        if(!$playlistPodcast)
            throw $this->createNotFoundException('The podcast does not exist in that playlist');

        $em->remove($playlistPodcast);
        $em->flush();

        return $this->redirect($this->generateUrl('playlist_details', array('id' => $playlistId)));
    }
}