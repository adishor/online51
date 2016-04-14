<?php

namespace AppBundle\Security\Sonata;

use Sonata\MediaBundle\Security\DownloadStrategyInterface;
use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use AppBundle\Service\UserHelperService;

class UserDownloadStrategy implements DownloadStrategyInterface
{
    protected $translator;
    protected $authorizationChecker;
    protected $tokenStorage;
    protected $userHelper;

    public function __construct(TranslatorInterface $translator, AuthorizationCheckerInterface $authorizationChecker, TokenStorage $tokenStorage, UserHelperService $userHelper)
    {
        $this->translator = $translator;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->userHelper = $userHelper;
    }

    /**
     * @param \Sonata\MediaBundle\Model\MediaInterface  $media
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function isGranted(MediaInterface $media, Request $request)
    {
        if (!$this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY') && !$this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

            throw new AccessDeniedHttpException();
        }
        $user = $this->tokenStorage->getToken()->getUser();
        $validDocuments = $this->userHelper->getValidUserDocuments($user->getId());
        if (!isset($validDocuments[$media->getId()])) {
            throw new AccessDeniedHttpException();
        }

        return true;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->translator->trans('description.user_download_strategy', array(), 'SonataMediaBundle');
    }

}