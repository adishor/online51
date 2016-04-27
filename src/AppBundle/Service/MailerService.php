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
    protected $emailTitle;
    protected $translator;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, TranslatorInterface $translator, $emailFrom, $contactEmail, $emailTitle)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->translator = $translator;
        $this->emailFrom = $emailFrom;
        $this->contactEmail = $contactEmail;
        $this->emailTitle = $emailTitle;
    }

    public function sendMessage($emailTo, $subject, $body, $attachment = null)
    {
        $subject = $this->emailTitle . ' ' . $subject;
        $message = \Swift_Message::newInstance()
          ->setSubject($subject)
          ->setFrom($this->emailFrom)
          ->setTo($emailTo)
          ->setBody($body, 'text/html');
        if (null !== $attachment) {
            $message->attach($attachment);
        }

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

        $this->sendMessage($contact->getEmail(), $this->translator->trans('mail.confirm.subject'), $confirmationBody);
    }

    public function sendResetPasswordMessage($user)
    {
        $confirmationBody = $this->templating->render('user/reset_password_email_body.html.twig', array(
            'name' => $user->getName(),
            'token' => $user->getConfirmationToken())
          , 'text/html');

        $this->sendMessage($user->getEmail(), $this->translator->trans('mail.reset-password.subject'), $confirmationBody);
    }

    public function sendOrderConfirmationMessage($order)
    {
        $user = $order->getUser();
        $subscription = ($order->getSubscription()) ? $order->getSubscription()->getName() : null;
        $orderBody = $this->templating->render('order/order_confirmation_email_body.html.twig', array(
            'name' => $user->getName(),
            'number' => $order->getId(),
            'subscription' => $subscription,
            'domains' => $order->getDomains(),
            'domainAmount' => $order->getDomainAmount(),
            'credits' => $order->getCreditValue(),
            'endDate' => $order->getEndingDate(),
            'mentions' => $order->getMentions(),
          ), 'text/html');

        $this->sendMessage($user->getEmail(), $this->translator->trans('mail.order-confirm.subject'), $orderBody);
    }

    public function sendActivationMessage($user)
    {
        $confirmationBody = $this->templating->render('user/activate_account_email_body.html.twig', array(
            'name' => $user->getName(),
            'token' => $user->getConfirmationToken())
          , 'text/html');

        $this->sendMessage($user->getEmail(), $this->translator->trans('mail.activate-account.subject'), $confirmationBody);
    }

    public function sendOrderInvoice($order, $attachment)
    {
        $user = $order->getUser();
        $subscription = ($order->getSubscription()) ? $order->getSubscription()->getName() : null;
        $orderBody = $this->templating->render('order/order_confirmation_email_body.html.twig', array(
            'name' => $user->getName(),
            'number' => $order->getId(),
            'subscription' => $subscription,
            'domains' => $order->getDomains(),
            'domainAmount' => $order->getDomainAmount(),
            'credits' => $order->getCreditValue(),
            'endDate' => $order->getEndingDate(),
            'mentions' => $order->getMentions(),
          ), 'text/html');


        $this->sendMessage($user->getEmail(), $this->translator->trans('mail.order-confirm.subject'), $orderBody, $attachment);
    }

}