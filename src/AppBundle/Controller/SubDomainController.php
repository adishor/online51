<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Folder;
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

        $repo = $this->getDoctrine()->getRepository('AppBundle:File');
        $files = $repo->getFilesBySubdomain($subdomain->getId());

        $isUserException = false;

        if ($this->getUser()) {
            $userId = $this->getUser()->getId();
            $userService = $this->get('app.user');

            $isUserException = $userService->getIsUserException($userId);
        }

        return $this->render('subdomain/show.html.twig', array(
              'domain' => $domain,
              'subdomain' => $subdomain,
              'files' => $files,
              'isUserException' => $isUserException,
        ));
    }

    /**
     * @Route("/section/{domain}/{subdomain}/{folder}", name="folder_show")
     * @ParamConverter("domain", options={"mapping": {"domain": "slug"}})
     * @ParamConverter("subdomain", options={"mapping": {"subdomain": "slug"}})
     * @ParamConverter("folder", options={"mapping": {"folder": "id"}})
     */
    public function showFolderAction(Domain $domain, SubDomain $subdomain, Folder $folder)
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:File');
        $files = $repo->getFilesByFolderOrganizedByType($folder->getId());
        $isUserException = $this->get('app.user')->getIsUserException($this->getUser()->getId());

        return $this->render('subdomain/folder_show.html.twig', array(
            'domain' => $domain,
            'subdomain' => $subdomain,
            'folder' => $folder,
            'files' => $files,
//            'isValid' => $isValid,
//            'validDocuments' => $validDocuments,
//            'validVideos' => $validVideos,
            'isUserException' => $isUserException,
        ));

        $isValid = $isUserException = false;
        $validDocuments = $validVideos = null;

        if ($this->getUser()) {
            $userId = $this->getUser()->getId();
            $userService = $this->get('app.user');
            $creditsUsageService = $this->get('app.credits_usage');
            $isValid = $userService->isDomainValidForUser($userId, $domain->getId());
//            $validDocuments = $creditsUsageService->getValidUserDocuments($userId, $domain->getId(), $subdomain->getId());
//            $validVideos = $creditsUsageService->getValidUserVideos($userId, $domain->getId(), $subdomain->getId());
            $isUserException = $userService->getIsUserException($userId);
        }

        return $this->render('subdomain/folder_show.html.twig', array(
            'domain' => $domain,
            'subdomain' => $subdomain,
            'isValid' => $isValid,
            'validDocuments' => $validDocuments,
            'validVideos' => $validVideos,
            'isUserException' => $isUserException,
        ));
    }

}