<?php

namespace AppBundle\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class CreditsUsageController extends Controller
{

    public function getDocumentCreditValueAction(Request $request)
    {
        $creditValue = '';

        $documentId = $request->request->get('documentId');
        $document = $this->getDoctrine()->getRepository('AppBundle:Document')->find($documentId);
        if ($document) {
            $creditValue = $document->getCreditValue();
        }

        return new Response(json_encode($creditValue), 200);
    }

    public function getVideoCreditValueAction(Request $request)
    {
        $creditValue = '';

        $videoId = $request->request->get('videoId');
        $video = $this->getDoctrine()->getRepository('AppBundle:Video')->find($videoId);
        if ($video) {
            $creditValue = $video->getCreditValue();
        }

        return new Response(json_encode($creditValue), 200);
    }

}