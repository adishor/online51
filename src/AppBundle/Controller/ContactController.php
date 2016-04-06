<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Contact;
use Gregwar\CaptchaBundle\Type\CaptchaType;

class ContactController extends Controller
{

    /**
     * @Route("/contact", name="contact")
     */
    public function showContactAction(Request $request)
    {
        $contact = new Contact();

        $contactForm = $this->createFormBuilder($contact)
          ->add('name', 'text')
          ->add('email', 'text')
          ->add('phone', 'text')
          ->add('subject', 'text')
          ->add('message', 'textarea')
          ->add('save', 'submit')
          ->add('captcha', 'captcha', array(
              'width' => 200,
              'height' => 40,
              'length' => 6,
          ))
          ->getForm();

        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            

            return $this->redirect($this->generateUrl('contact'));
        }
        return $this->render('contact/contact.html.twig', array(
              'contactForm' => $contactForm->createView()
        ));
    }

}