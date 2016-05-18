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
     * @Route("/unlock-formular", name="unlock_formular")
     */
    public function unlockFormularAction(Request $request)
    {
        $user = $this->getUser();
        if (null === $user) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('domain.not-logged-in'));
        }
        $userHelper = $this->get('app.user_helper');
        $userHelper->updateValidUserCredits();
        $formularId = $request->request->get('formularId');
        $formular = $this->getDoctrine()->getManager()->getRepository('AppBundle:Formular')->find($formularId);

        $formularConfig = $request->request->get('data');

        if (!$userHelper->getIsUserException()) {
            if (true === $userHelper->isValidUserFormular($user->getId(), $formularId, $formularConfig)) {
                throw new AccessDeniedHttpException($this->get('translator')->trans('domain.document.already-unlocked'));
            }
            if (($user->getCreditsTotal() - $formular->getCreditValue() < 0) || (null === $user->getCreditsTotal())) {
                $response = new Response(json_encode(array('success' => false, 'message' => $this->get('translator')->trans('domain.formular.no-credits'))));

                return $response;
            }
        }

        $userHelper->createUnlockFormularCreditUsage($user, $formular, $formularConfig);

        $response = new Response(json_encode(array(
              'success' => true,
              'message' => $this->get('translator')->trans('domain.formular.success'),
              'credits' => $user->getCreditsTotal(),
              'formHash' => md5(json_encode($user->getId()) . json_encode($formularConfig))
        )));

        return $response;
    }

}