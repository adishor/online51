<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\Type\RegisterType;
use AppBundle\Form\Type\ResetPasswordType;
use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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

    /**
     * @Route("/forgot_password", name="forgot_password")
     */
    public function forgotPasswordAction(Request $request)
    {
        $email = $request->request->get('email');
        $forgotPasswordErrors = [];
        $user = $this->container->get('fos_user.user_manager')->findUserByUsernameOrEmail($email);

        if (null === $user) {
            $forgotPasswordErrors['Msg'] = $this->get('translator')->trans('reset-response.no-user');

            return new Response(json_encode($forgotPasswordErrors), 200);
        }

        if (null === $user->getConfirmationToken()) {
            /** @var $tokenGenerator TokenGeneratorInterface */
            $tokenGenerator = $this->container->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $this->container->get('session')->set('session_mail', $email);
        $this->container->get('app.mailer')->sendResetPasswordMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->container->get('fos_user.user_manager')->updateUser($user);

        return new Response(json_encode($forgotPasswordErrors), 200);
    }

    /**
     * @Route("/reset_password/{token}", name="reset_password")
     */
    public function resetPasswordAction(Request $request, $token)
    {
        $user = $this->container->get('fos_user.user_manager')->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException($this->get('translator')->trans('reset-response.link-invalid'));
        }
        $hours = $this->getParameter('reset_password_hours');
        $now = new \DateTime();

        if ($now > ($user->getPasswordRequestedAt()->add(new \DateInterval("PT{$hours}H")))) {

            $user->setConfirmationToken(null);
            $user->setPasswordRequestedAt(null);
            $this->container->get('fos_user.user_manager')->updateUser($user);
            throw new AccessDeniedHttpException($this->get('translator')->trans('reset-response.link-expired'));
        }

        $reset = new User();
        $form = $this->createForm(new ResetPasswordType(), $reset);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->get('app.user_helper')->changePassword($reset, $token);
            $this->addFlash('successful-reset', 'success.reset');
            $user->setConfirmationToken(null);
            $user->setPasswordRequestedAt(null);
            $this->container->get('fos_user.user_manager')->updateUser($user);

            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('user/reset.html.twig', array(
              'form' => $form->createView(),
        ));
    }

}