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

            $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
//            $data = $serializer->serialize($contact, 'json');
            $data = $serializer->toArray($contact);

            $formular = new \AppBundle\Entity\Formular();
            $formular->setCreditValue(2);
            $formular->setName('test');
            $formular->setValabilityDays(2);
            $formular->setField1($data);

            $em = $this->getDoctrine()->getManager();
            $em->persist($formular);
            $em->flush();

            $serializer2 = \JMS\Serializer\SerializerBuilder::create()->build();
            $aa = $this->getDoctrine()->getManager()->getRepository('AppBundle:Formular')->find($formular->getId());
            var_dump($aa->getField1());
//            var_dump(json_decode($aa->getField1()));
            var_dump($serializer2->fromArray($aa->getField1(), 'AppBundle\Form\Type\ContactType'));
            die;

//            $this->get('app.mailer')->sendContactMessage($contact);
//            $this->get('app.mailer')->sendConfirmationMessage($contact);
//            $this->addFlash('contactSuccess', 'contact.label.success');
            //return $this->redirectToRoute('contact');
        }

        return $this->render('contact/contact.html.twig', array(
              'contactForm' => $contactForm->createView()
        ));
    }

}