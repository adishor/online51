<?php
/**
 * Created by PhpStorm.
 * User: adriancalugar
 * Date: 07/02/2017
 * Time: 18:59
 */


namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Application\Sonata\MediaBundle\Entity\Media;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;

class MediaController extends Controller
{
    /**
     * @Route("/media/show/{fileId}", name="media_show")
     * @ParamConverter("media", options={"id" = "fileId"})
     */
    public function showFormularAction(Request $request, Media $media)
    {
        $user = $this->getUser();
        if (null === $user) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('domain.not-logged-in'));
        }

        $pathtofile = $this->container->get('sonata.media.twig.extension')->path($media, 'reference');
        $rootpath = $this->get('kernel')->getRootDir() . '/../web';

        $fullpath = $rootpath . $pathtofile;

        if ($media->getContentType() !== 'application/pdf') {
            $response = new Response();
            $response->headers->set('Content-type', $media->getContentType());
            $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $fullpath));
            $response->headers->set('Content-Length', filesize($fullpath));
            return $response;
        }

        $response = new BinaryFileResponse($fullpath);

        $response->headers->set('Content-Type', 'application/pdf');
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $media->getName());

        return $response;
    }
}