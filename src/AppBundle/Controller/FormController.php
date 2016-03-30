<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\Type\RegisterType;
use Application\Sonata\UserBundle\Entity\User;

class FormController extends Controller
{
    /**
     * @Route("/register", name="register")
     */
    public function showRegisterAction()
    {
        $register = new User();
        $form = $this->createForm(new RegisterType(), $register);

        return $this->render('form/register.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function showContactAction()
    {
        return $this->render('form/contact.html.twig');
    }

    /**
     * @Route("/newsletter", name="newsletter")
     */
    public function showNewsletterAction()
    {
        return $this->render('form/newsletter.html.twig');
    }
}

