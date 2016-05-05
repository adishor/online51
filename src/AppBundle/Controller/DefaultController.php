<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    /**
     *
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $domains = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Domain')->findBy(array('deleted' => false));

        $subscriptions = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Subscription')->findBy(array('deleted' => false));

        $isUserException = false;
        if ($this->getUser()) {
            $isUserException = $this->get('app.user_helper')->getIsUserException($this->getUser()->getId());
        }

        return $this->render('default/index.html.twig', array(
              'domains' => $domains,
              'subscriptions' => $subscriptions,
              'isUserException' => $isUserException,
        ));
    }

    public function menuAction()
    {
        $domains = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Domain')->findBy(array('deleted' => false));

        return $this->render('default/menu.html.twig', array(
              'domains' => $domains
        ));
    }

    /**
     *
     * @Route("/advantages", name="advantages")
     */
    public function showAdvantagesAction()
    {
        return $this->render('default/advantages.html.twig');
    }

    /**
     *
     * @Route("/bonuses", name="bonuses")
     */
    public function showBonusesAction()
    {
        return $this->render('default/bonuses.html.twig');
    }

    /**
     *
     * @Route("/faq", name="faq")
     */
    public function showFAQAction()
    {
        return $this->render("default/faq.html.twig");
    }

    /**
     *
     * @Route("/terms", name="terms")
     */
    public function showTermsAction()
    {
        return $this->render("default/terms.html.twig");
    }

    /**
     *
     * @Route("/news", name="news")
     */
    public function showNewsAction()
    {
        return $this->render("default/news.html.twig");
    }

    public function subscriptionsNavAction()
    {
        $subscriptions = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Subscription')->findBy(array('deleted' => false));

        return $this->render('default/subscriptionsNav.html.twig', array(
              'subscriptions' => $subscriptions
        ));
    }

}