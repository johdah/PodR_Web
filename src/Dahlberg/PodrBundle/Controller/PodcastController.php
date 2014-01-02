<?php

namespace Dahlberg\PodrBundle\Controller;

use Dahlberg\PodrBundle\Entity\Podcast;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class PodcastController extends Controller
{
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
}
