<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CreditsUsageController extends Controller
{

    /**
     * @Route("/unlock-document", name="unlock_document")
     */
    public function unlockDocumentAction(Request $request)
    {
        $user = $this->getUser();
        if (null === $user) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('domain.not-logged-in'));
        }
        $userHelper = $this->get('app.user_helper');
        $userHelper->updateValidUserCredits();
        $documentId = $request->request->get('documentId');
        $document = $this->getDoctrine()->getManager()->getRepository('AppBundle:Document')->find($documentId);

        if (true === $userHelper->isValidUserDocument($user->getId(), $documentId)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('domain.document.already-unlocked'));
        }
        if (($user->getCreditsTotal() - $document->getCreditValue() < 0) || (null === $user->getCreditsTotal())) {
            $response = new Response(json_encode(array('success' => false, 'message' => $this->get('translator')->trans('domain.document.no-credits'))));

            return $response;
        }

        $userHelper->createUnlockDocumentCreditUsage($user, $document);

        $response = new Response(json_encode(array(
              'success' => true,
              'message' => $this->get('translator')->trans('domain.document.success'),
              'credits' => $user->getCreditsTotal(),
        )));

        return $response;
    }

    /**
     * @Route("/unlock-video", name="unlock_video")
     */
    public function unlockVideoAction(Request $request)
    {
        $user = $this->getUser();
        if (null === $user) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('domain.not-logged-in'));
        }
        $userHelper = $this->get('app.user_helper');
        $userHelper->updateValidUserCredits();
        $videoId = $request->request->get('videoId');
        $video = $this->getDoctrine()->getManager()->getRepository('AppBundle:Video')->find($videoId);

        if (true === $userHelper->isValidUserVideo($user->getId(), $videoId)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('domain.video.already-unlocked'));
        }
        if (($user->getCreditsTotal() - $video->getCreditValue() < 0) || (null === $user->getCreditsTotal())) {
            $response = new Response(json_encode(array('success' => false, 'message' => $this->get('translator')->trans('domain.video.no-credits'))));

            return $response;
        }

        $userHelper->createUnlockVideoCreditUsage($user, $video);

        $response = new Response(json_encode(array(
              'success' => true,
              'message' => $this->get('translator')->trans('domain.video.success'),
              'credits' => $user->getCreditsTotal(),
        )));

        return $response;
    }

    /**
     * @Route("/unlock-formular", name="unlock_formular")
     */
    public function unlockFormularAction(Request $request)
    {
        $user = $this->getUser();
        if (null === $user) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('domain.not-logged-in'));
        }

        $formularId = $request->request->get('formularId');
        $formularConfig = $request->request->get('data');

        return $this->processFormularAction($formularId, $formularConfig);
    }

    /**
     * @Route("/createNewFormularDocument", name="unlock_formular_from_old")
     */
    public function createNewFormularDocumentAction(Request $request)
    {
        $user = $this->getUser();
        if (null === $user) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('domain.not-logged-in'));
        }

        $creditUsageId = $request->request->get('creditUsageId');
        $creditUsage = $this->getDoctrine()->getManager()->getRepository('AppBundle:CreditsUsage')->find($creditUsageId);
        if ($creditUsage->getUser()->getId() != $user->getId()) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('invalid.data'));
        }

        $formularId = $creditUsage->getFormular()->getId();
        $formularData = $creditUsage->getFormData();
        $formularConfig = (json_decode($creditUsage->getFormConfig())) ? get_object_vars(json_decode($creditUsage->getFormConfig())) : null;
        if (isset($formularConfig['an'])) {
            $formularConfig['an'] = $formularConfig['an'] + 1;
        }
        $discountedIsDraft = !$creditUsage->getIsFormConfigFinished();

        return $this->processFormularAction($formularId, $formularConfig, true, $discountedIsDraft, $formularData);
    }

    public function processFormularAction($formularId, $formularConfig, $discounted = false, $discountedIsDraft = false, $formularData = NULL)
    {
        $user = $this->getUser();
        $userHelper = $this->get('app.user_helper');
        $formular = $this->getDoctrine()->getManager()->getRepository('AppBundle:Formular')->find($formularId);
        if (!$userHelper->getIsUserException()) {
            $creditValue = ($discounted) ? $formular->getDiscountedCreditValue() : $formular->getCreditValue();
            if (($user->getCreditsTotal() - $creditValue < 0) || (null === $user->getCreditsTotal())) {
                $response = new Response(json_encode(array('success' => false, 'message' => $this->get('translator')->trans('domain.formular.no-credits'))));
                return $response;
            }
        }

        $userHelper->updateValidUserCredits();

        $formularService = $this->get("app.formular." . $formular->getSlug());
        $formularService->setName($formular->getSlug());
        $entity = $formularService->getEntity();
        $isDraft = ($discounted) ? $discountedIsDraft : !$entity::$oneStepFormConfig;
        if ($isDraft) {
            $this->get('session')->getFlashBag()->add('form-error', 'domain.formular.no-credits-used');
        }

        $creditsUsageId = $userHelper->createUnlockFormularCreditUsage($user, $formular, $formularConfig, $isDraft, $discounted, $formularData);

        $response = new Response(json_encode(array(
              'success' => true,
              'message' => $this->get('translator')->trans('domain.formular.success'),
              'credits' => $user->getCreditsTotal(),
              'formSlug' => $formular->getSlug(),
              'formHash' => md5(json_encode($user->getId()) . json_encode($formularConfig)),
              'creditsUsageId' => $creditsUsageId,
        )));

        return $response;
    }

}