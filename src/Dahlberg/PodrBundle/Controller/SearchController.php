<?php
// src/Dahlberg/PodrBundle/Controller/SearchController.php;
namespace Dahlberg\PodrBundle\Controller;

use Dahlberg\PodrBundle\Form\Type\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller {
    public function indexAction(Request $request) {
        $user = $this->get('security.context')->getToken()->getUser();
        $form = $this->createForm(new SearchType());
        $form->handleRequest($request);

        $episodes = null;
        $podcasts = null;
        $scope = "none";

        if($form->isValid()) {
            $data = $this->prepareDataOptions($form);

            $scope = ($form->get('scope')->getData() == null) ? "both" : $form->get('scope')->getData();

            if($form->get('scope')->getData() == null || $form->get('scope')->getData() == "episode") {
                $episodes = $repository = $this->getDoctrine()->getRepository('DahlbergPodrBundle:UserEpisode')->findByDataOptions($data, $user);
            }
            if($form->get('scope')->getData() == null || $form->get('scope')->getData() == "podcast") {
                $podcasts = $repository = $this->getDoctrine()->getRepository('DahlbergPodrBundle:UserPodcast')->findByDataOptions($data, $user);
            }
        }

        return $this->render('DahlbergPodrBundle:Search:index.html.twig', array(
            'episodes'  => $episodes,
            'podcasts'  => $podcasts,
            'form'      => $form->createView(),
            'scope'     => $scope
        ));
    }

    private function prepareDataOptions($form)
    {
        $data = array();
        if($form->has('searchterm'))
            $data['searchterm'] = $form->get('searchterm')->getData();

        return $data;
    }
}