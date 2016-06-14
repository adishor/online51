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
                if ($this->getUser()) {
                    $countyName = $this->getUser()->getCounty()->getName();
                } else {
                    try {
                        $location = json_decode(file_get_contents('http://freegeoip.net/json/' . $_SERVER['REMOTE_ADDR']));
                    } catch (Exception $ex) {

                    }

                    if (isset($location->region_name)) {
                        $countyName = str_replace("Judetul ", "", $location->region_name);
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