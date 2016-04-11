<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\Type\RegisterType;
use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    /**
     * @Route("/register", name="register")
     */
    public function showRegisterAction(Request $request)
    {
        $register = new User();
        $form = $this->createForm(new RegisterType(), $register);

        $form->handleRequest($request);

        $registerErrors = array();
        if ($form->isSubmitted()) {
            if ($form->get('cui')->getData()) {
                $registerErrors['cui'] = $this->get('app.user_helper')->checkCUI($form->get('cui')->getData());
            }
            if ($form->get('iban')->getData()) {
                $registerErrors['iban'] = $this->get('app.user_helper')->checkIBAN($form->get('iban')->getData());
            }
        }
        if ($form->isSubmitted() && $form->isValid() && !in_array(false, $registerErrors)) {

            $this->get('app.user_helper')->addUserToDatabase($register);
            $this->addFlash('successful-register', 'success.register');

            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('user/register.html.twig', array(
              'form' => $form->createView(),
              'registerErrors' => $registerErrors,
        ));
    }

    /**
     * @Route("/newsletter", name="newsletter")
     */
    public function showNewsletterAction()
    {
        return $this->render('user/newsletter.html.twig');
    }

    /**
     * @Route("/ajax_localities", name="ajax_localities")
     */
    public function getAjaxLocalitiesAction(Request $request)
    {
        $jsonCities = [];

        $countyId = $request->request->get('countyId');
        $county = $this->getDoctrine()->getRepository('AppBundle:ROCounty')->find($countyId);
        if ($county) {
            $cities = $county->getCities();

            foreach ($cities as $city) {
                $jsonCities[$city->getId()] = $city->getName();
            }
        }

        return new Response(json_encode($jsonCities), 200);
    }

}