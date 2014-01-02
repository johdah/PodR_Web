<?php
// src/Dahlberg/PodrBundle/Controller/PodcastController.php;
namespace Dahlberg\PodrBundle\Controller;

use Dahlberg\PodrBundle\Entity\Episode;
use Dahlberg\PodrBundle\Entity\Podcast;
use Dahlberg\PodrBundle\Lib\PodcastParser;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class PodcastController extends Controller {
    public function detailsAction($id) {
        $podcast = $this->getDoctrine()
            ->getRepository('DahlbergPodrBundle:Podcast')
            ->find($id);

        if(!$podcast)
            throw $this->createNotFoundException('The podcast does not exist');

        return $this->render('DahlbergPodrBundle:Podcast:details.html.twig', array(
            'podcast' => $podcast,
        ));
    }

    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->add('feedurl', 'url', array(
                'required' => true
            ))
            ->add('save', 'submit')
            ->getForm();
        $form->handleRequest($request);
        $formSuccess = null;

        if ($form->isValid()) {
            $podcast = new Podcast();
            $podcast->setFeedurl($form->get('feedurl')->getData());

            $em->persist($podcast);
            // TODO: Need to check if podcast already exists in a better way
            try {
                $em->flush();
                $formSuccess = "Podcast added!";
            } catch(DBALException $e) {
                $form->addError(new FormError("Can't add that podcast. Maybe it already exists"));
            }
        }

        $podcasts = $this->getDoctrine()
            ->getRepository('DahlbergPodrBundle:Podcast')
            ->findAll();

        return $this->render('DahlbergPodrBundle:Podcast:index.html.twig', array(
            'podcasts' => $podcasts,
            'form' => $form->createView(),
            'formSuccess' => $formSuccess
        ));
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

    public function updater(Podcast $podcast) {
        $em = $this->getDoctrine()->getManager();

        //$userPodcasts = UserPodcast::where('podcast_id', '=', $podcast->id);

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

                // Add a UserEpisode for every follower
                /*foreach($userPodcasts as $userPodcast) {
                    $userEpisode = new UserEpisode();
                    $userEpisode->episode_id = $episode->id;
                    $userEpisode->podcast_id = $podcast->id;
                    $userEpisode->user_id = $userPodcast->user_id;
                    $userEpisode->save();
                }*/
            }

            $parser->next();
        }
    }
}
