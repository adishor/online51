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

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $checkUser = $this->getDoctrine()->getManager()
                    ->getRepository('ApplicationSonataUserBundle:User')
                    ->findByEmail($data->getEmail());
            if ($checkUser) {
                $this->addFlash('error-register', 'Email is already used by an account.');
            }
            else {
                $user = new User();
                $user->setUsername($data->getEmail());
                $user->setUsernameCanonical($data->getEmail());
                $user->setEmail($data->getEmail());
                $user->setEmailCanonical($data->getEmail());
                $user->setEnabled(true);
                $user->setExpired(false);
                $user->setLocked(false);
                $user->setName($data->getName());
                $user->setCompany($data->getCompany());
                $user->setCui($data->getCui());
                $user->setNoRegistrationORC($data->getNoRegistrationORC());
                $user->setNoEmployees($data->getNoEmployees());
                $user->setNoCertifiedEmpowerment($data->getNoCertifiedEmpowerment());
                $user->setIban($data->getIban());
                $user->setBank($data->getBank());
                $user->setPhone($data->getPhone());
                $user->setCounty($data->getCounty());
                $user->setCity($data->getCity());
                $user->setAddress($data->getAddress());
                $user->setUploadImage($data->getUploadImage());
                $user->setFunction($data->getFunction());
                $user->addRole(User::ROLE_DEFAULT);
                $encoder = $this->get('security.encoder_factory')->getEncoder($user);
                $user->setPassword($encoder->encodePassword($data->getPassword(), $user->getSalt()));

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->addFlash('successfull-register', 'User was saved');
                return $this->redirect($this->generateUrl('homepage'));
            }
        }
        else {
             $this->addFlash('error-register', $form->getErrorsAsString());
        }

        return $this->render('user/register.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function showContactAction()
    {
        return $this->render('user/contact.html.twig');
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
