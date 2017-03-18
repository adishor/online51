<?php
/**
 * Created by PhpStorm.
 * User: adriancalugar
 * Date: 12/02/2017
 * Time: 14:16
 */

namespace AppBundle\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VideoController extends Controller
{

    public function getFoldersAction(Request $request)
    {
        $subdomainId = $request->get('subdomainId');
        $html = ""; // HTML as response
        $subdomain = $this->getDoctrine()
            ->getRepository('AppBundle:SubDomain')
            ->find($subdomainId);

        if (!empty($subdomain)) {
            $folders = $subdomain->getFolders();

            foreach ($folders as $fd) {
                $html .= '<option value="' . $fd->getId().'" >' . $fd->getName() . '</option>';
            }

            return new Response($html, 200);
        }

        return new Response();
    }
}