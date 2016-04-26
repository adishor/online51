<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\CreditsUsage;
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

        $documentId = $request->request->get('documentId');
        if (true === $this->get('app.user_helper')->isValidUserDocument($user->getId(), $documentId)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('domain.document.already-unlocked'));
        }
        $em = $this->getDoctrine()->getManager();

        $creditsUsage = new CreditsUsage();
        $creditsUsage->setUser($user);
        $document = $em->getRepository('ApplicationSonataMediaBundle:Media')->find($documentId);
        $creditsUsage->setDocument($document);

        if (($creditsUsage->getUser()->getCreditsTotal() - $document->getCreditValue() < 0) || (null === $creditsUsage->getUser()->getCreditsTotal())) {
            $response = new Response(json_encode(array('success' => false, 'message' => $this->get('translator')->trans('domain.document.no-credits'))));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        $creditsUsage->getUser()->setCreditsTotal($creditsUsage->getUser()->getCreditsTotal() - $document->getCreditValue());
        $creditsUsage->getUser()->setLastCreditUpdate(new \DateTime());
        $creditsUsage->setMentions($this->get('translator')->trans('credit-usage.unlocked-by-user'));
        $expireDate = new \DateTime();
        $expireDate->add(new \DateInterval('P' . $creditsUsage->getDocument()->getValabilityDays() . 'D'));
        $creditsUsage->setExpireDate($expireDate);
        $creditsUsage->setCredit($creditsUsage->getDocument()->getCreditValue());
        $em->persist($creditsUsage);
        $em->flush();

        $response = new Response(json_encode(array(
              'success' => true,
              'message' => $this->get('translator')->trans('domain.document.success'),
              'credits' => $creditsUsage->getUser()->getCreditsTotal()
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}