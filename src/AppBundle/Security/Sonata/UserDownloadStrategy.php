<?php

namespace AppBundle\Security\Sonata;

use Sonata\MediaBundle\Security\DownloadStrategyInterface;
use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use AppBundle\Service\UserService;
use AppBundle\Service\CreditsUsageService;

class UserDownloadStrategy implements DownloadStrategyInterface
{
    protected $translator;
    protected $authorizationChecker;
    protected $tokenStorage;
    protected $userService;
    protected $creditsUsageService;

    public function __construct(TranslatorInterface $translator, AuthorizationCheckerInterface $authorizationChecker, TokenStorage $tokenStorage, UserService $userService, CreditsUsageService $creditsUsageService)
    {
        $this->translator = $translator;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->userService = $userService;
        $this->creditsUsageService = $creditsUsageService;
    }

    /**
     * @param \Sonata\MediaBundle\Model\MediaInterface  $media
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function isGranted(MediaInterface $media, Request $request)
    {
        if (!$this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY') &&
          !$this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

            throw new AccessDeniedHttpException();
        }
        $user = $this->tokenStorage->getToken()->getUser();
        if ($this->userService->getIsUserException($user->getId())) {

            return true;
        }

        if (empty($this->creditsUsageService->getValidCreditsUsageForMedia($media->getId()))) {
            throw new AccessDeniedHttpException();
        }

        $creditsUsage = $this->creditsUsageService->getValidCreditsUsageForMedia($media->getId())[0];


        if (null !== $creditsUsage->getDocument()) {
            if (!$this->creditsUsageService->isValidUserDocument($user->getId(), $creditsUsage->getDocument()->getId())) {
                throw new AccessDeniedHttpException();
            }
        }
        if (null !== $creditsUsage->getVideo()) {
            if (!$this->creditsUsageService->isValidUserVideo($user->getId(), $creditsUsage->getVideo()->getId())) {
                throw new AccessDeniedHttpException();
            }
        }
        if (null !== $creditsUsage->getFormular()) {
            if (!$this->creditsUsageService->isValidUserFormularDocument($user->getId(), $creditsUsage->getFormular()->getId())) {
                throw new AccessDeniedHttpException();
            }
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