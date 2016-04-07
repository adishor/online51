<?php

namespace AppBundle\Service;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

class MailerService
{
    protected $mailer;
    protected $templating;
    protected $emailFrom;
    protected $contactEmail;
    protected $translator;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, TranslatorInterface $translator, $emailFrom, $contactEmail)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->translator = $translator;
        $this->emailFrom = $emailFrom;
        $this->contactEmail = $contactEmail;
    }

    public function sendMessage($emailTo, $subject, $body)
    {

        $message = \Swift_Message::newInstance()
          ->setSubject($subject)
          ->setFrom($this->emailFrom)
          ->setTo($emailTo)
          ->setBody($body, 'text/html');
        $this->mailer->send($message);
    }

    public function sendContactMessage($contact)
    {
        $subject = $this->translator->trans('mail.contact.subject') . $contact->getSubject();

        $contactBody = $this->templating->render('contact/contact_email_body.html.twig', array(
            'name' => $contact->getName(),
            'phone' => $contact->getPhone(),
            'email' => $contact->getEmail(),
            'message' => $contact->getMessage()), 'text/html');

        $this->sendMessage($this->contactEmail, $subject, $contactBody);
    }

    public function sendConfirmationMessage($contact)
    {
        $confirmationBody = $this->templating->render('contact/confirmation_email_body.html.twig', array(
            'name' => $contact->getName(),
            'subject' => $contact->getSubject(),
            'message' => $contact->getMessage()), 'text/html');

        $this->sendMessage($contact->getEmail(), $this->translator->trans('mail.contact.confirm-subject'), $confirmationBody);
    }

}