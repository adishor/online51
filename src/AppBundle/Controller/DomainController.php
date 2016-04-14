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
        $user = $this->getUser();
        $userHelper = $this->get('app.user_helper');
        return $this->render('domain/show.html.twig', array(
              'domain' => $domain,
              'isValid' => $userHelper->isDomainValidForUser($user, $domain),
              'validDocuments' => $userHelper->getValidUserDocuments($user, $domain),
        ));
    }

}