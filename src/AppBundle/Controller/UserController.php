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
use Application\Sonata\MediaBundle\Entity\Media;

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

            if (null === $register->getConfirmationToken()) {
                /** @var $tokenGenerator TokenGeneratorInterface */
                $tokenGenerator = $this->container->get('fos_user.util.token_generator');
                $register->setConfirmationToken($tokenGenerator->generateToken());
            }
            $this->container->get('app.mailer')->sendActivationMessage($register);
            $this->get('app.user_helper')->addUserToDatabase($register);

            return $this->render('user/register-success.html.twig');
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
        $errors = [];
        $user = $this->container->get('fos_user.user_manager')->findUserByUsernameOrEmail($email);

        if ((null === $user) || ($user->getDeleted())) {
            $errors['Msg'] = $this->get('translator')->trans('json-response.no-user');

            return new Response(json_encode($errors), 200);
        }
        if (!$user->isEnabled()) {
            $errors['Msg'] = $this->get('translator')->trans('json-response.not-enabled-user');

            return new Response(json_encode($errors), 200);
        }

        if (null === $user->getConfirmationToken()) {
            /** @var $tokenGenerator TokenGeneratorInterface */
            $tokenGenerator = $this->container->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $this->container->get('app.mailer')->sendResetPasswordMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->container->get('fos_user.user_manager')->updateUser($user);

        return new Response(json_encode($errors), 200);
    }

    /**
     * @Route("/reset_password/{token}", name="reset_password")
     */
    public function resetPasswordAction(Request $request, $token)
    {
        $user = $this->container->get('fos_user.user_manager')->findUserByConfirmationToken($token);

        if ((null === $user) || ($user->getDeleted())) {
            throw new NotFoundHttpException($this->get('translator')->trans('json-response.link-invalid'));
        }
        if (!$user->isEnabled()) {
            throw new NotFoundHttpException($this->get('translator')->trans('json-response.link-invalid'));
        }
        $hours = $this->getParameter('reset_password_hours');
        $now = new \DateTime();

        if (($user->getPasswordRequestedAt() === null) || ($now > ($user->getPasswordRequestedAt()->add(new \DateInterval("PT{$hours}H"))))) {

            $user->setConfirmationToken(null);
            $user->setPasswordRequestedAt(null);
            $this->container->get('fos_user.user_manager')->updateUser($user);
            throw new AccessDeniedHttpException($this->get('translator')->trans('json-response.link-expired'));
        }

        $reset = new User();
        $form = $this->createForm(new ResetPasswordType(), $reset);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->get('app.user_helper')->changePassword($reset, $user);
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

    /**
     * @Route("/activate-account/{token}", name="activate_account")
     */
    public function activateAccountAction(Request $request, $token)
    {
        $user = $this->container->get('fos_user.user_manager')->findUserByConfirmationToken($token);

        if ((null === $user) || $user->isEnabled() || ($user->getDeleted())) {
            throw new NotFoundHttpException($this->get('translator')->trans('activate-account.link-invalid'));
        }
        if (false === $user->isEnabled()) {
            $user->setEnabled(true);
            $this->addFlash('successful-activate', 'success.activate');
            $this->container->get('fos_user.user_manager')->updateUser($user);
        }

        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
     * @Route("/change/password", name="change_password")
     */
    public function changePasswordAction(Request $request)
    {
        $user = $this->getUser();

        $change = new User();
        $form = $this->createForm(new ResetPasswordType(), $change);
        $form->add('oldPassword', 'password', array('mapped' => false));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && ($this->get('app.user_helper')->checkOldPassword($form->get('oldPassword')->getData(), $user))) {
            $this->addFlash('successful-change', 'success.change');
            $this->get('app.user_helper')->changePassword($change, $user);
            return $this->redirect($this->generateUrl('homepage'));
        }
        return $this->render('user/change_password.html.twig', array(
              'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/change/information", name="change_info")
     */
    public function changeInformationAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(new registerType(), $user);
        $form->remove('password')->remove('captcha');

        $form->handleRequest($request);

        $changeInfoErrors = array();
        if ($form->isSubmitted()) {
            if ($form->get('cui')->getData()) {
                $changeInfoErrors['cui'] = $this->get('app.user_helper')->checkCUI($form->get('cui')->getData());
            }
            if ($form->get('iban')->getData()) {
                $changeInfoErrors['iban'] = $this->get('app.user_helper')->checkIBAN($form->get('iban')->getData());
            }
        }
        if ($form->isSubmitted() && $form->isValid() && !in_array(false, $changeInfoErrors)) {

            if ($user->getImage()->getProviderName() === 'sonata.media.provider.image') {
                $media = $user->getImage();
                $media->setMediaType(Media::IMAGE_TYPE);
                $user->setImage($media);
            }
            $this->container->get('fos_user.user_manager')->updateUser($user);
            $this->addFlash('successful-change-info', 'success.change-info');

            return $this->redirect($this->generateUrl('change_info'));
        }

        return $this->render('user/change_info.html.twig', array(
              'form' => $form->createView(),
              'changeInfoErrors' => $changeInfoErrors,
        ));
    }

    /**
     * @Route("/resend-activation", name="resend_activation_email")
     */
    public function resendActivationAction(Request $request)
    {
        $email = $request->request->get('email');
        $errors = [];
        $user = $this->container->get('fos_user.user_manager')->findUserByUsernameOrEmail($email);

        if ((null === $user) || ($user->getDeleted())) {
            $errors['Msg'] = $this->get('translator')->trans('json-response.no-user');

            return new Response(json_encode($errors), 200);
        }
        if ($user->isEnabled()) {
            $errors['Msg'] = $this->get('translator')->trans('json-response.enabled-user');

            return new Response(json_encode($errors), 200);
        }

        if (null === $user->getConfirmationToken()) {
            /** @var $tokenGenerator TokenGeneratorInterface */
            $tokenGenerator = $this->container->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }
        $this->container->get('app.mailer')->sendActivationMessage($user);
        $this->container->get('fos_user.user_manager')->updateUser($user);

        return new Response(json_encode($errors), 200);
    }

}