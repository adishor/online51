<?php

namespace AppBundle\Controller;

use AppBundle\Entity\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class CreditsUsageController extends Controller
{

    /**
     * @Route("/unlock-file/{file}", name="unlock_file")
     * @ParamConverter("file", options={"mapping": {"file": "id"}})
     */
    public function unlockFile(File $file)
    {
        $user = $this->getUser();
        if (null === $user) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('domain.not-logged-in'));
        }

        $creditsUsageService = $this->get('app.credits_usage');
        $creditsUsageService->createUnlockDocumentCreditUsage($user, $file);

        $response = new Response(json_encode(array(
            'success' => true,
            'message' => $this->get('translator')->trans('domain.document.success'),
            'credits' => $user->getCreditsTotal(),
        )));

        return $response;
    }

    /**
     * @Route("/unlock-document", name="unlock_document")
     */
    public function unlockDocumentAction(Request $request)
    {
        $user = $this->getUser();
        if (null === $user) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('domain.not-logged-in'));
        }
        $creditsUsageService = $this->get('app.credits_usage');
        $creditsUsageService->updateValidUserCredits();

        $documentId = $request->request->get('documentId');
        $document = $this->getDoctrine()->getManager()->getRepository('AppBundle:Document')->find($documentId);

        if (true === $creditsUsageService->isValidUserDocument($user->getId(), $documentId)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('domain.document.already-unlocked'));
        }

        if (($user->getCreditsTotal() - $document->getCreditValue() < 0) || (null === $user->getCreditsTotal())) {
            $response = new Response(json_encode(array('success' => false, 'message' => $this->get('translator')->trans('domain.document.no-credits'))));

            return $response;
        }

        $creditsUsageService->createUnlockDocumentCreditUsage($user, $document);

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

        $creditsUsageService = $this->get('app.credits_usage');

        $videoId = $request->request->get('videoId');
        $video = $this->getDoctrine()->getManager()->getRepository('AppBundle:Video')->find($videoId);

        if (true === $creditsUsageService->isValidUserVideo($user->getId(), $videoId)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('domain.video.already-unlocked'));
        }

        if (($user->getCreditsTotal() - $video->getCreditValue() < 0) || (null === $user->getCreditsTotal())) {
            $response = new Response(json_encode(array('success' => false, 'message' => $this->get('translator')->trans('domain.video.no-credits'))));

            return $response;
        }

        $creditsUsageService->createUnlockVideoCreditUsage($user, $video);

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
        $formularConfig = $request->request->get('data', array());

        return $this->processFormularAction($formularId, $formularConfig);
    }

    /**
     * @Route("/createNewFormularDocument", name="unlock_formular_from_old")
     */
    public function createNewFormularDocumentAction(Request $request)
    {
        $creditUsageId = $request->request->get('creditUsageId');
        $creditUsage = $this->getDoctrine()->getManager()->getRepository('AppBundle:FormularCreditsUsage')->find($creditUsageId);

        if ($creditUsage->getUser()->getId() != $this->getUser()->getId()) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('invalid.data'));
        }

        $formularId = $creditUsage->getFormular()->getId();
        $creditUsageFormularConfig = $creditUsage->getFormularConfig();
        $formularData = $creditUsageFormularConfig->getFormData();

        return $this->processFormularAction($formularId, array(), true, $formularData);
    }

    /**
     * @Route("/createNewEgdFormularDocument", name="unlock_egd_formular_from_old")
     */
    public function createNewEgdFormularDocumentAction(Request $request)
    {
        $creditUsageId = $request->request->get('creditUsageId');
        $creditUsage = $this->getDoctrine()->getManager()->getRepository('AppBundle:EgdFormularCreditsUsage')->find($creditUsageId);

        if ($creditUsage->getUser()->getId() != $this->getUser()->getId()) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('invalid.data'));
        }

        $formularId = $creditUsage->getFormular()->getId();
        $creditUsageFormularConfig = $creditUsage->getFormularConfig();
        $formularData = $creditUsageFormularConfig->getFormData();

        $formularConfig = (json_decode($creditUsageFormularConfig->getFormConfig())) ? get_object_vars(json_decode($creditUsageFormularConfig->getFormConfig())) : null;

        return $this->processFormularAction($formularId, $formularConfig, true, $formularData);
    }

    private function processFormularAction($formularId, $formularConfig = array(), $discounted = false, $formularData = null)
    {
        $user = $this->getUser();
        $userService = $this->get('app.user');
        $creditsUsageService = $this->get('app.credits_usage');
        $formular = $this->getDoctrine()->getManager()->getRepository('AppBundle:Formular')->find($formularId);

        if (!$userService->getIsUserException()) {
            $creditValue = ($discounted) ? $formular->getDiscountedCreditValue() : $formular->getCreditValue();

            if (($user->getCreditsTotal() - $creditValue < 0) || (null === $user->getCreditsTotal())) {
                $response = new Response(json_encode(array(
                    'success' => false,
                    'message' => $this->get('translator')->trans('domain.formular.no-credits'),
                )));
                return $response;
            }
        }

        $creditsUsageId = $creditsUsageService->createUnlockFormularCreditUsage($user, $formular, $formularConfig, $discounted, $formularData);

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

    /**
     * @Route("/update-title", name="update_title_document")
     */
    public function updateDocumentTitleAction(Request $request)
    {
        $user = $this->getUser();
        if (null === $user) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('domain.not-logged-in'));
        }

        $creditUsageId = $request->request->get('creditUsageId');
        $creditsUsage = $this->getDoctrine()->getManager()->getRepository('AppBundle:CreditsUsage')->find($creditUsageId);
        if ((!$creditsUsage) || ($creditsUsage->getUser()->getId() != $user->getId())) {
            return new Response(json_encode(array(
                  'success' => false,
                  'message' => $this->get('translator')->trans('invalid.data')
            )));
        }

        $title = $request->request->get('title');
        $creditsUsage->setTitle($title);

        $em = $this->getDoctrine()->getManager();
        $em->persist($creditsUsage);
        $em->flush();

        return new Response(json_encode(array(
              'success' => true
        )));
    }

    /**
     * @Route("/get-agent-quantities", name="get_agent_quantities")
     */
    public function getAgentQuantities(Request $request)
    {
        $user = $this->getUser();
        if (null === $user) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('domain.not-logged-in'));
        }

        $creditUsageId = $request->request->get('creditUsageId');
        $creditsUsage = $this->getDoctrine()->getManager()->getRepository('AppBundle:CreditsUsage')->find($creditUsageId);
        if ((!$creditsUsage) || ($creditsUsage->getUser()->getId() != $user->getId())) {
            return new Response(json_encode(array(
                  'success' => false,
                  'message' => $this->get('translator')->trans('invalid.data')
            )));
        }

        $index = $request->request->get('indexMonth');
        $formData = json_decode($creditsUsage->getFormData());

        $agentsQuantity = [];
        foreach ($formData->_e_g_d1_generare_deseuri[$index]->agent_economic as $key => $agent) {
            $agentsQuantity[$key] = $agent->cantitate_deseu;
        }

        return new Response(json_encode(array('success' => true, 'agentsQuantity' => $agentsQuantity)));
    }


}