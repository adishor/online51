<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdController extends Controller
{

    public function showAdsAction()
    {
        if (null === $this->get('session')->get('areas')) {
            $areas = array();

            $countyName = '';
            if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
                if ($this->getUser() && $this->getUser()->getProfile()->getCounty()) {
                    $countyName = $this->getUser()->getProfile()->getCounty()->getName();
                } else {
                    $location = file_get_contents('http://api.ipstack.com/' . $_SERVER['REMOTE_ADDR']. '?access_key=34dc748a78e24c736067b1550e87224e');
                    if (FALSE !== $location) {
                        $location = json_decode($location);
                        if (isset($location->region_name)) {
                            $countyName = str_replace("Judetul ", "", $location->region_name);
                        }
                    }
                }
            }

            if ($countyName) {
                $areasCounty = $this->getDoctrine()->getManager()->getRepository('AppBundle:ROArea')
                  ->findAllByCounty(strtolower(str_replace(array("/", " "), array("-", ""), $countyName)));
                if (count($areasCounty)) {
                    foreach ($areasCounty as $areaItem) {
                        $areas[] = $areaItem->getSlug();
                    }
                }
            } else {
                $areas[] = $this->getParameter('default_ads_area_slug');
            }
            $this->get('session')->set('areas', $areas);
        }

        $ads = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Ad')->findAllByAreas($this->get('session')->get('areas'));

        return $this->render('default/ads.html.twig', array(
              'ads' => $ads,
        ));
    }

}