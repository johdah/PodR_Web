<?php
// src/Dahlberg/PodrBundle/Controller/PodcastController.php;
namespace Dahlberg\PodrBundle\Controller;

use Dahlberg\PodrBundle\Entity\Episode;
use Dahlberg\PodrBundle\Entity\Podcast;
use Dahlberg\PodrBundle\Entity\UserEpisode;
use Dahlberg\PodrBundle\Entity\UserPodcast;
use Dahlberg\PodrBundle\Lib\OpmlParser;
use Dahlberg\PodrBundle\Lib\PodcastParser;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class PodcastController extends Controller {
    public function detailsAction($id) {
        $user = $this->get('security.context')->getToken()->getUser();
        $podcast = $this->getDoctrine()
            ->getRepository('DahlbergPodrBundle:Podcast')
            ->find($id);
        $userPodcast = $this->getDoctrine()
            ->getRepository('DahlbergPodrBundle:UserPodcast')
            ->findOneBy(array('podcast' => $podcast, 'user' => $user));

        if(!$podcast)
            throw $this->createNotFoundException('The podcast does not exist');

        return $this->render('DahlbergPodrBundle:Podcast:details.html.twig', array(
            'podcast' => $podcast,
            'userPodcast' => $userPodcast,
            'user'  => $user
        ));
    }

    public function dislikeAction($id) {
        $em = $this->getDoctrine()->getManager();

        $userPodcast = $this->prepareUserPodcast($id);
        $userPodcast->setRating(-1);
        $userPodcast->setDateUpdated(new \DateTime('NOW'));

        $em->persist($userPodcast);
        $em->flush();

        return $this->redirect($this->generateUrl('podcast_details', array('id' => $id)));
    }

    public function followAction($id) {
        $em = $this->getDoctrine()->getManager();

        $userPodcast = $this->prepareUserPodcast($id);
        $userPodcast->setFollowing(true);
        $userPodcast->setDateUpdated(new \DateTime('NOW'));

        $em->persist($userPodcast);
        $em->flush();

        return $this->redirect($this->generateUrl('podcast_details', array('id' => $id)));
    }

    public function importAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->add('opmlfile', 'file', array(
                'required' => true
            ))
            ->add('save', 'submit')
            ->getForm();
        $form->handleRequest($request);
        $formSuccess = null;

        if ($form->isValid()) {
            $fs = new Filesystem();
            $user = $this->get('security.context')->getToken()->getUser();
            $file = $form->get('opmlfile')->getData();

            $dir = $this->get('kernel')->getRootDir() . '/../web/uploads/opmlimport';
            $filename = rand(1, 99999).'.opml';
            $file->move($dir, $filename);

            $parser = new OpmlParser($dir . '/' . $filename);

            // TODO: Foreach Podcast
            if($parser->errors == null) {
                while($parser->hasNext()) {
                    $parser->next();

                    if($this->getDoctrine()->getRepository('DahlbergPodrBundle:Podcast')->findOneByFeedurl($parser->getBodyXmlUrl()) != null) continue;
                    $podcast = new Podcast();
                    $podcast->setFeedurl($parser->getBodyXmlUrl());
                    $podcast->setTitle(($parser->getBodyTitle() != null) ? $parser->getBodyTitle() : "Unknown title");
                    // TODO: Better way to check if podcast is unique?
                    $em->persist($podcast);

                    //$this->updater($podcast);

                    $userPodcast = new UserPodcast();
                    $userPodcast->setUser($user);
                    $userPodcast->setPodcast($podcast);
                    $userPodcast->setFollowing(true);
                    $em->persist($userPodcast);
                }
            }

            // Remove the OPML-file
            $fs->remove($dir . '/' . $filename);
            try {
                $em->flush();
                $formSuccess = "Podcast added!";
            } catch(DBALException $e) {
                $form->addError(new FormError("Can't add that podcast. Maybe it already exists"));
            }
        }

        return $this->render('DahlbergPodrBundle:Podcast:import.html.twig', array(
            'form' => $form->createView(),
            'formSuccess' => $formSuccess
        ));
    }

    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $form = $this->createFormBuilder()
            ->add('feedurl', 'url', array(
                'required' => true
            ))
            ->add('add', 'submit')
            ->getForm();
        $form->handleRequest($request);
        $formSuccess = null;

        if ($form->isValid()) {
            $podcast = $this->preparePodcast($form->get('feedurl')->getData());
            $em->persist($podcast);
            //$em->flush();

            $userPodcast = $this->prepareUserPodcast($podcast->getId());
            $userPodcast->setFollowing(true);
            $userPodcast->setDateUpdated(new \DateTime('NOW'));

            $em->persist($userPodcast);
            $em->flush();

            // TODO: Need to check if podcast already exists in a better way
            try {
                $em->flush();
                $formSuccess = "Podcast added!";
            } catch(DBALException $e) {
                $form->addError(new FormError("Can't add that podcast. Maybe it already exists"));
            }
        }

        $podcasts = $em->getRepository('DahlbergPodrBundle:UserPodcast')
            ->findAllPodcastsOrderedByTitle($user);

        return $this->render('DahlbergPodrBundle:Podcast:index.html.twig', array(
            'podcasts' => $podcasts,
            'form' => $form->createView(),
            'formSuccess' => $formSuccess
        ));
    }

    public function likeAction($id) {
        $em = $this->getDoctrine()->getManager();

        $userPodcast = $this->prepareUserPodcast($id);
        $userPodcast->setRating(1);
        $userPodcast->setDateUpdated(new \DateTime('NOW'));

        $em->persist($userPodcast);
        $em->flush();

        return $this->redirect($this->generateUrl('podcast_details', array('id' => $id)));
    }

    public function starAction($id) {
        $em = $this->getDoctrine()->getManager();

        $userPodcast = $this->prepareUserPodcast($id);
        $userPodcast->setStarred(true);
        $userPodcast->setDateUpdated(new \DateTime('NOW'));

        $em->persist($userPodcast);
        $em->flush();

        return $this->redirect($this->generateUrl('podcast_details', array('id' => $id)));
    }

    public function unfollowAction($id) {
        $em = $this->getDoctrine()->getManager();

        $userPodcast = $this->prepareUserPodcast($id);
        $userPodcast->setFollowing(false);
        $userPodcast->setDateUpdated(new \DateTime('NOW'));

        $em->persist($userPodcast);
        $em->flush();

        return $this->redirect($this->generateUrl('podcast_details', array('id' => $id)));
    }

    public function unstarAction($id) {
        $em = $this->getDoctrine()->getManager();

        $userPodcast = $this->prepareUserPodcast($id);
        $userPodcast->setStarred(false);
        $userPodcast->setDateUpdated(new \DateTime('NOW'));

        $em->persist($userPodcast);
        $em->flush();

        return $this->redirect($this->generateUrl('podcast_details', array('id' => $id)));
    }

    public function updateAction($id) {
        $podcast = $this->getDoctrine()
            ->getRepository('DahlbergPodrBundle:Podcast')
            ->find($id);

        if(!$podcast)
            throw $this->createNotFoundException('The podcast does not exist');

        $this->updater($podcast);

        return $this->redirect($this->generateUrl('podcast_index'));
    }

    /* NOT ACTIONS */

    public function preparePodcast($feedurl) {
        $podcast = $this->getDoctrine()
            ->getRepository('DahlbergPodrBundle:Podcast')
            ->findOneBy(array('feedurl' => $feedurl));

        if(!$podcast) {
            $podcast = new Podcast();
            $podcast->setFeedurl($feedurl);
        }

        return $podcast;
    }

    public function prepareUserEpisode($episode, $user) {
        if(!$episode)
            throw $this->createNotFoundException('The episode does not exist');

        $userEpisode = $this->getDoctrine()
            ->getRepository('DahlbergPodrBundle:UserEpisode')
            ->findOneBy(array('episode' => $episode, 'user' => $user));

        if(!$userEpisode) {
            $userEpisode = new UserEpisode();
            $userEpisode->setEpisode($episode);
            $userEpisode->setUser($user);
        }

        return $userEpisode;
    }

    public function prepareUserPodcast($podcastId) {
        $user = $this->get('security.context')->getToken()->getUser();
        $podcast = $this->getDoctrine()
            ->getRepository('DahlbergPodrBundle:Podcast')
            ->find($podcastId);

        if(!$podcast)
            throw $this->createNotFoundException('The podcast does not exist');

        $userPodcast = $this->getDoctrine()
            ->getRepository('DahlbergPodrBundle:UserPodcast')
            ->findOneBy(array('podcast' => $podcast, 'user' => $user));

        if(!$userPodcast) {
            $userPodcast = new UserPodcast();
            $userPodcast->setPodcast($podcast);
            $userPodcast->setUser($user);
        }

        return $userPodcast;
    }

    public function updater(Podcast $podcast) {
        $em = $this->getDoctrine()->getManager();

        $markAsUnreadSeparator = new \DateTime('NOW');
        $markAsUnreadSeparator->modify('-10 day');

        $parser = new PodcastParser($podcast->getFeedurl());
        $podcast->setCopyright($parser->getPodcastCopyright());
        $podcast->setDescription($parser->getPodcastDescription());
        $podcast->setLanguage($parser->getPodcastLanguage());
        $podcast->setLink($parser->getPodcastLink());
        $podcast->setTitle(($parser->getPodcastTitle() && trim($parser->getPodcastTitle()) !== "") ? $parser->getPodcastTitle() : "Unknown title");
        $podcast->setItunesAuthor($parser->getEpisodeItunesAuthor());
        $podcast->setItunesBlock($parser->getEpisodeItunesBlock());
        $podcast->setItunesComplete($parser->getPodcastItunesComplete());
        $podcast->setItunesExplicit($parser->getPodcastItunesExplicit());
        $podcast->setItunesImage($parser->getPodcastItunesImage());
        $podcast->setItunesOwnerEmail($parser->getPodcastItunesOwnerEmail());
        $podcast->setItunesOwnerName($parser->getPodcastItunesOwnerName());
        $podcast->setItunesSubtitle($parser->getPodcastItunesSubtitle());
        $podcast->setItunesSummary($parser->getPodcastItunesSummary());
        $podcast->setDateUpdated(new \DateTime('NOW'));

        $em->persist($podcast);
        $em->flush();

        $userPodcasts = $this->getDoctrine()
            ->getRepository('DahlbergPodrBundle:UserPodcast')
            ->findAll(array('podcast' => $podcast));

        // TODO: Need to be more effective
        while($parser->exists()) {
            $episode = $this->getDoctrine()
                ->getRepository('DahlbergPodrBundle:Episode')
                ->findOneByGuid($parser->getEpisodeGuid());

            if (!$episode) {
                $episode = new Episode();
                $episode->setPodcast($podcast);
                $episode->setEnclosureLength($parser->getEpisodeEnclosureLength());
                $episode->setEnclosureType($parser->getEpisodeEnclosureType());
                $episode->setEnclosureUrl($parser->getEpisodeEnclosureURL());
                $episode->setGuid($parser->getEpisodeGuid());
                $episode->setItunesAuthor($parser->getEpisodeItunesAuthor());
                $episode->setItunesBlock($parser->getEpisodeItunesBlock());
                $episode->setItunesDuration($parser->getEpisodeItunesDuration());
                $episode->setItunesExplicit($parser->getEpisodeItunesExplicit());
                $episode->setItunesIsClosedCaption($parser->getEpisodeItunesIsClosedCaption());
                $episode->setItunesImage($parser->getEpisodeItunesImage());
                $episode->setItunesSubtitle($parser->getEpisodeItunesSubtitle());
                $episode->setItunesSummary($parser->getEpisodeItunesSummary());
                $episode->setPublishedDate($parser->getEpisodePubDate());
                $episode->setTitle($parser->getEpisodeTitle());
                $episode->setDateAdded(new \DateTime('NOW'));
                $episode->setDateUpdated(new \DateTime('NOW'));
                $em->persist($episode);
                $em->flush();

                if($markAsUnreadSeparator > $episode->getPublishedDate())
                    continue; // Don't mark as read if older than 10 days

                // Add a UserEpisode for every follower
                foreach($userPodcasts as $userPodcast) {
                    $userEpisode = $this->prepareUserEpisode($episode, $userPodcast->getUser());

                    $em->persist($userEpisode);
                    $em->flush();
                }
            }

            $parser->next();
        }
    }
}
