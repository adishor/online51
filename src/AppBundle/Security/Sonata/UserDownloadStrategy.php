<?php

namespace AppBundle\Security\Sonata;

use Sonata\MediaBundle\Security\DownloadStrategyInterface;
use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Translation\TranslatorInterface;

class UserDownloadStrategy implements DownloadStrategyInterface
{
    protected $translator;
    protected $authorizationChecker;
    protected $tokenStorage;

    public function __construct(TranslatorInterface $translator, AuthorizationCheckerInterface $authorizationChecker, TokenStorage $tokenStorage)
    {
        $this->translator = $translator;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param \Sonata\MediaBundle\Model\MediaInterface  $media
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function isGranted(MediaInterface $media, Request $request)
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY') || $this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->tokenStorage->getToken()->getUser();
            return true;
        }
        //need to check if document is valid for user
        return false;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->translator->trans('description.user_download_strategy', array(), 'SonataMediaBundle');
    }

}