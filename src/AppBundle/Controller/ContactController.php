<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Contact;
use AppBundle\Form\Type\ContactType;

class ContactController extends Controller
{

    /**
     * @Route("/contact", name="contact")
     */
    public function showContactAction(Request $request)
    {
        $contact = new Contact();

        $contactForm = $this->createForm(new ContactType(), $contact);

        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $this->get('app.mailer')->sendContactMessage($contact);
            $this->get('app.mailer')->sendConfirmationMessage($contact);
            $this->addFlash('contactSuccess', 'contact.label.success');

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/contact.html.twig', array(
              'contactForm' => $contactForm->createView()
        ));
    }

}