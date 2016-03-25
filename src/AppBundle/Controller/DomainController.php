<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Domain;

class DomainController extends Controller
{
    /**
     * @Route("/section/{domain}", name="domain_show")
     * @ParamConverter("domain", options={"mapping": {"domain": "slug"}})
     */
    public function showDomainAction(Domain $domain)
    {
        return $this->render('domain/show.html.twig', array(
            'domain' => $domain
        ));
    }
}

