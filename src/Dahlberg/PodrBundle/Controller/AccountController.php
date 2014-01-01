<?php
// src/Dahlberg/PodrBundle/Controller/AuthController.php;
namespace Dahlberg\PodrBundle\Controller;

use Dahlberg\PodrBundle\Entity\Role;
use Dahlberg\PodrBundle\Form\Model\Registration;
use Dahlberg\PodrBundle\Form\Type\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class AccountController extends Controller {
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new RegistrationType(), new Registration());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $registration = $form->getData();
            $user = $registration->getUser();
            $role = new Role();
            $role->setRole('ROLE_USER');
            $user->addRole($role);

            $factory = $this->container->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($user->getPassword(), $user->getSalt()); //where $user->password has been bound in plaintext by the form
            $user->setPassword($password);

            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('login'));
        }

        return $this->render(
            'DahlbergPodrBundle:Account:register.html.twig',
            array('form' => $form->createView())
        );
    }

    public function loginAction(Request $request) {
        $session = $request->getSession();

        // Get the login error if there is one
        if($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'DahlbergPodrBundle:Account:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error' => $error,
            )
        );
    }

    public function registerAction()
    {
        $registration = new Registration();
        $form = $this->createForm(new RegistrationType(), $registration, array(
            'action' => $this->generateUrl('account_create'),
        ));

        return $this->render(
            'DahlbergPodrBundle:Account:register.html.twig',
            array('form' => $form->createView())
        );
    }
}