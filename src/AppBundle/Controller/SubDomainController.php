<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Domain;
use AppBundle\Entity\SubDomain;

class SubDomainController extends Controller
{

    /**
     * @Route("/section/{domain}/{subdomain}", name="subdomain_show")
     * @ParamConverter("domain", options={"mapping": {"domain": "slug"}})
     * @ParamConverter("subdomain", options={"mapping": {"subdomain": "slug"}})
     */
    public function showSubDomainAction(Domain $domain, SubDomain $subdomain)
    {

        $isValid = false;
        $validDocuments = null;
        $validVideos = null;
        $isUserException = false;
        if ($this->getUser()) {
            $userId = $this->getUser()->getId();
            $userService = $this->get('app.user');
            $creditsUsageService = $this->get('app.credits_usage');
            $isValid = $userService->isDomainValidForUser($userId, $domain->getId());
            $validDocuments = $creditsUsageService->getValidUserDocuments($userId, $domain->getId(), $subdomain->getId());
            $validVideos = $creditsUsageService->getValidUserVideos($userId, $domain->getId(), $subdomain->getId());
            $isUserException = $userService->getIsUserException($userId);
        }
        return $this->render('subdomain/show.html.twig', array(
              'domain' => $domain,
              'subdomain' => $subdomain,
              'isValid' => $isValid,
              'validDocuments' => $validDocuments,
              'validVideos' => $validVideos,
              'isUserException' => $isUserException,
        ));
    }

}