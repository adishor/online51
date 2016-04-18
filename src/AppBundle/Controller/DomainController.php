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

        $isValid = false;
        $validDocuments = null;
        $isUserException = false;
        if ($this->getUser()) {
            $userId = $this->getUser()->getId();
            $domainId = $domain->getId();
            $userHelper = $this->get('app.user_helper');
            $isValid = $userHelper->isDomainValidForUser($userId, $domainId);
            $validDocuments = $userHelper->getValidUserDocuments($userId, $domainId);
            $isUserException = $userHelper->getIsUserException($userId);
        }
        return $this->render('domain/show.html.twig', array(
              'domain' => $domain,
              'isValid' => $isValid,
              'validDocuments' => $validDocuments,
              'isUserException' => $isUserException,
        ));
    }

}