<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Formular;
use AppBundle\Entity\CreditsUsage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Application\Sonata\MediaBundle\Entity\Media;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;

class FormularController extends Controller
{

    /**
     * @Route("/showFormular/{slug}/{creditsUsageId}", name="formular_show")
     * @ParamConverter("formular")
     * @ParamConverter("creditsUsage", options={"id" = "creditsUsageId"})
     */
    public function showFormularAction(Request $request, Formular $formular, CreditsUsage $creditsUsage)
    {

        $user = $this->getUser();
        $formularService = $this->get('app.formular.' . $formular->getSlug());
        $formularService->setName($formular->getSlug());

        if ($mesage = $formularService->checkValidity($user, $creditsUsage)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans($mesage));
        }

        if (empty($creditsUsage->getFormData())) {
            $entity = $formularService->getEntity();
            $formData = new $entity();
            if (method_exists($formularService, 'applyDefaultFormData')) {
                $formularService->applyDefaultFormData($creditsUsage, $formData, $user);
            }
        } else {
            $formData = $this->get('jms_serializer')
              ->deserialize($creditsUsage->getFormData(), $formularService->getEntity(), 'json');
        }

        $flow = $this->get('app.form.flow.' . $formular->getSlug()); // must match the flow's service id
        if ($request->getMethod() == 'GET' && null === $request->query->get('step')) {
            $flow->reset();
        }
        $flow->bind($formData);

        // form of the current step
        $form = $flow->createForm();
        if (method_exists($formularService, 'applyFormCustomization')) {
            $formularService->applyFormCustomization($flow, $form, $creditsUsage);
        }

        $response = $this->handleForm($formularService, $creditsUsage, $flow, $form, $formData);
        if ($response) {
            return $this->redirect($this->generateUrl('show_valid_documents') . '?mediaId=' . $response);
        }

        $formTemplateData = (method_exists($formularService, 'calculateExtraTemplateData')) ? $formularService->calculateExtraTemplateData($formData) : null;

        return $this->render('document_form/' . strtolower($formular->getSlug()) . ".html.twig", array(
              'form' => $form->createView(),
              'flow' => $flow,
              'creditsUsage' => $creditsUsage,
              'formTemplateData' => $formTemplateData,
              'isUserException' => $this->get('app.user_helper')->getIsUserException(),
        ));
    }

    public function handleForm($formularService, $creditsUsage, &$flow, &$form, &$formData)
    {
        if ($flow->isValid($form)) {
            $flow->saveCurrentStepData($form);
            if (method_exists($formularService, 'processHandleForm')) {
                $formularService->processHandleForm($creditsUsage, $flow, $formData);
            }
            $nextStep = $flow->nextStep();
            if (!$nextStep && method_exists($formularService, 'processEndHandleForm')) {
                $formularService->processEndHandleForm($formData);
            }
            $creditsUsage->setFormData($this->get('jms_serializer')->serialize($formData, 'json'));
            $this->getDoctrine()->getManager()->flush();

            if ($nextStep || $this->get('request')->request->has('btnSave')) {
                // form for the next step
                $form = $flow->createForm();
            } else {
                // flow finished
                $flow->reset(); // remove step data from the session

                $media = $this->generateDocument($formularService, $creditsUsage, $formData);
                $creditsUsage->setMedia($media);
                $this->getDoctrine()->getManager()->flush();

                $this->get('session')->getFlashBag()->set('document-generated-success', 'success.document-generated');

                return $media->getId();
            }

            return false;
        }
    }

    public function generateDocument($formularService, $creditsUsage, $formData)
    {

        $filename = $formularService->getName() . $creditsUsage->getId() . '.pdf';
        $filePath = $this->getParameter('generated_documents_dir') . strtolower($formularService->getSlug()) . '/' . $filename;
        $formTemplateData = (method_exists($formularService, 'calculateExtraTemplateData')) ? $formularService->calculateExtraTemplateData($formData) : null;
        $fileBody = $this->renderView('document_pdf_template/' . strtolower($formularService->getSlug()) . ".html.twig", array(
            'data' => $formData,
            'templateData' => $formTemplateData,
            'formConfig' => json_decode($creditsUsage->getFormConfig())
        ));
        $this->get('knp_snappy.pdf')->generateFromHtml($fileBody, $filePath);

        $file = new UploadedFile($filePath, $filename);
        $media = new Media();
        $media->setBinaryContent($file);
        $media->setName($filename);
        $media->setProviderName('sonata.media.provider.file');
        $media->setContext('default');
        $media->setMediaType($media::FORM_GENERATED_TYPE);
        $this->getDoctrine()->getManager()->persist($media);
        $this->getDoctrine()->getManager()->flush();
        $fs = new Filesystem();
        $fs->remove($filePath);

        return $media;
    }

    /**
     * @Route("/configFormular/{slug}", name="formular_config")
     * @ParamConverter("formular")
     */
    public function configFormularUniquenessAction(Formular $formular, Request $request)
    {
        $name = str_replace("_", "", $formular->getSlug());
        $entity = "AppBundle\\Entity\\DocumentForm\\" . $name . "\\" . $name;

        $uniqueValues = [];

        if (isset($entity::$uniqueness) && null !== $entity::$uniqueness) {
            //set values for YEAR - 2 cases depends by ValabilityMonth
            if (in_array($entity::UNIQUE_AN, $entity::$uniqueness)) {
                $uniqueValues[$entity::UNIQUE_AN] = [];
                $currYear = date('Y');

                if (null !== $formular->getValabilityMonth()) {
                    $currMonth = date('n');
                    $uniqueValues[$entity::UNIQUE_AN][$currYear] = $currYear;
                    if ($currMonth <= $formular->getValabilityMonth()) {
                        $uniqueValues[$entity::UNIQUE_AN][$currYear - 1] = $currYear - 1;
                    }
                } else {
                    for ($i = $this->getParameter('formular.startYear'); $i <= $currYear; $i++) {
                        $uniqueValues[$entity::UNIQUE_AN][$i] = $i;
                    }
                }
            }

            foreach ($entity::$uniqueness as $unique) {
                if ($unique != $entity::UNIQUE_AN) {
                    $uniqueValues[$unique] = $this->getParameter($unique);
                }
            }

            return $this->render('document_form/config/config_form_uniqueness.html.twig', array(
                  'uniqueValues' => $uniqueValues,
                  'formular' => $formular,
                  'isUserException' => $this->get('app.user_helper')->getIsUserException(),
                  'isDraft' => !$entity::$oneStepFormConfig,
            ));
        }

        return $this->render('document_form/config/no_config_form_uniqueness.html.twig', array(
              'formular' => $formular,
              'isUserException' => $this->get('app.user_helper')->getIsUserException(),
        ));
    }

    /**
     * @Route("/shortFormConfigurationText/{creditUsageId}", name="short_form_configuration_text")
     */
    public function getFormularDocumentsShortFormConfigurationTextAction($creditUsageId, $short = true)
    {
        $creditsUsage = $this->getDoctrine()->getManager()->getRepository('AppBundle:CreditsUsage')->find($creditUsageId);

        if ($creditsUsage->getFormConfig() && $creditsUsage->getFormConfig() != 'null') {
            $formularService = $this->get('app.formular.' . $creditsUsage->getFormular()->getSlug());
            $formularService->setName($creditsUsage->getFormular()->getSlug());
            $text = $formularService->getTextForFormConfig($creditsUsage->getFormConfig(), $short);

            return new Response($this->get('translator')->trans($text['message'], $text['variables']));
        }

        return new Response();
    }

}