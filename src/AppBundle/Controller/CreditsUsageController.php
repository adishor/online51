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
        $userHelper->unlockDocument($user, $documentId);

        $response = new Response(json_encode(array(
              'success' => true,
              'message' => $this->get('translator')->trans('domain.document.success'),
              'credits' => $user->getCreditsTotal()
        )));

        return $response;
    }

}