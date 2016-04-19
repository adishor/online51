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
            ->getRepository('AppBundle:Domain')->findAll();

        $subscriptions = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Subscription')->findAll();

        return $this->render('default/index.html.twig', array(
              'domains' => $domains,
              'subscriptions' => $subscriptions
        ));
    }

    public function menuAction()
    {
        $domains = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Domain')->findAll();

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

    /**
     *
     * @Route("/login", name="block_normal_login")
     */
    public function blockNormalLoginAction()
    {
        return $this->redirectToRoute("homepage");
    }

    public function subscriptionsNavAction()
    {
        $subscriptions = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Subscription')->findAll();

        return $this->render('default/subscriptionsNav.html.twig', array(
              'subscriptions' => $subscriptions
        ));
    }

}