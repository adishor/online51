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
        $name = $contact->getName();
        $subject = $contact->getSubject();
        $message = $contact->getMessage();
        $email = $contact->getEmail();

        $confirmationBody = $this->templating->render('contact/confirmation-email-body.html.twig', array(
            'name' => $name,
            'subject' => $subject,
            'message' => $message), 'text/html');
        $this->sendMessage($email, $this->translator->trans('mail.contact.confirm-subject'), $confirmationBody);

        $subject = $this->translator->trans('mail.contact.subject') . $subject;
        $contactBody = $this->templating->render('contact/contact-email-body.html.twig', array(
            'name' => $name,
            'phone' => $contact->getPhone(),
            'email' => $email,
            'message' => $message), 'text/html');
        $this->sendMessage($this->contactEmail, $subject, $contactBody);
    }

}