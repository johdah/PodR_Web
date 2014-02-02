<?php
// src/Dahlberg/PodrBundle/Controller/PlaylistController.php;
namespace Dahlberg\PodrBundle\Controller;

use Dahlberg\PodrBundle\Entity\Playlist;
use Dahlberg\PodrBundle\Entity\PlaylistPodcast;
use Dahlberg\PodrBundle\Lib\DataManipulator;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class PlaylistController extends Controller {
    public function detailsAction(Request $request, $id) {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $playlist = $em->getRepository('DahlbergPodrBundle:Playlist')->findOneBy(array('id' => $id, 'owner' => $user));
        $podcasts = $em->getRepository('DahlbergPodrBundle:Podcast')->findAllPodcastsByPlaylist($playlist);
        $episodes = $em->getRepository('DahlbergPodrBundle:UserEpisode')->findUnarchivedEpisodesByPlaylist($user, $playlist);

        $allPodcasts = $em->getRepository('DahlbergPodrBundle:Podcast')->findAllPodcastsByUser($user);
        $manipulator = new DataManipulator;
        $filteredPodcasts = $manipulator->associate($allPodcasts, 'id', 'title');

        $form = $this->createFormBuilder()
            ->add('podcasts', 'choice', array(
                'required' => true,
                'choices' => $filteredPodcasts
            ))
            ->add('add', 'submit')
            ->getForm();
        $form->handleRequest($request);
        $formSuccess = null;

        if ($form->isValid()) {
            $podcast = $em->getRepository('DahlbergPodrBundle:Podcast')->findOneById($form->get('podcasts')->getData());

            $playlistPodcast = $em->getRepository('DahlbergPodrBundle:PlaylistPodcast')->findOneBy(array('playlist' => $playlist, 'podcast' => $podcast));
            if($playlistPodcast == null) {
                $playlistPodcast = new PlaylistPodcast();
                $playlistPodcast->setPlaylist($playlist);
                $playlistPodcast->setPodcast($podcast);
            }

            $em->persist($playlistPodcast);
            try {
                $em->flush();
                $formSuccess = "Podcast(s) added to playlist!";
            } catch(DBALException $e) {
                $form->addError(new FormError("Can't add that podcast. Maybe it's already added"));
            }
        }

        return $this->render('DahlbergPodrBundle:Playlist:details.html.twig', array(
            'form'          => $form->createView(),
            'formSuccess'   => $formSuccess,
            'episodes'      => $episodes,
            'playlist'      => $playlist,
            'podcasts'      => $podcasts,
        ));
    }

    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $form = $this->createFormBuilder()
            ->add('title', 'text', array(
                'required' => true
            ))
            ->add('icon', 'choice', array(
                'required' => true,
                'choices' => array(
                    "heart"     => "Heart",
                    "like"      => "Like",
                    "marker"    => "Marker",
                )
            ))
            ->add('style', 'choice', array(
                'required' => true,
                'choices' => array(
                    "blue"                  => "Blue",
                    "blue-glowing"          => "Blue Glowing",
                    "darkblue"              => "Dark Blue",
                    "darkblue-glowing"      => "Dark Blue Glowing",
                    "darkgreen"             => "Dark Green",
                    "darkgreen-glowing"     => "Dark Green Glowing",
                    "darkpurple"            => "Dark Purple",
                    "darkpurple-glowing"    => "Dark Purple Glowing",
                    "darkred"               => "Dark Red",
                    "darkred-glowing"       => "Dark Red Glowing",
                    "darkyellow"            => "Dark Yellow",
                    "darkyellow-glowing"    => "Dark Yellow Glowing",
                    "green"                 => "Green",
                    "green-glowing"         => "Green Glowing",
                    "purple"                => "Purple",
                    "purple-glowing"        => "Purple Glowing",
                    "red"                   => "Red",
                    "red-glowing"           => "Red Glowing",
                    "yellow"                => "Yellow",
                    "yellow-glowing"        => "Yellow Glowing",
                )
            ))
            ->add('save', 'submit')
            ->getForm();
        $form->handleRequest($request);
        $formSuccess = null;

        if ($form->isValid()) {
            $playlist = new Playlist();
            $playlist->setIcon($form->get('icon')->getData());
            $playlist->setOwner($user);
            $playlist->setStyle($form->get('style')->getData());
            $playlist->setTitle($form->get('title')->getData());

            $em->persist($playlist);
            try {
                $em->flush();
                $formSuccess = "Playlist added!";
            } catch(DBALException $e) {
                $form->addError(new FormError("Can't add that playlist."));
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