<?php

namespace AppBundle\Controller;

use AppBundle\Document\UniqueDocumentInterface;
use AppBundle\Entity\FormularCreditsUsage;
use AppBundle\Helper\GeneralHelper;
use Doctrine\Common\Inflector\Inflector;
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
     * @Route("/configFormular/{slug}", name="formular_config")
     * @ParamConverter("formular")
     */
    public function configFormularUniquenessAction(Formular $formular, Request $request)
    {

        $formularId = GeneralHelper::getServiceIdBySlug($formular->getSlug());
        $formularService = $this->get('app.formular.' . $formularId);

        if ($formularService->hasController()) {
            $controllerName = GeneralHelper::getControllerNameBySlug($formular->getSlug());
            $this->forward('AppBundle:Formular\\' . $controllerName . ':configFormularUniqueness');
        }

        if ($formularService->hasToBeUnique()) {
            $uniqueValues = $this->get('app.formular.' . $formularId)->getUniquenessValues($formular);

            $entity = $formularService->getEntity();

            return $this->render('document_form/unique/' . $formularId . '_unique.html.twig', array(
                'uniqueValues' => $uniqueValues,
                'formular' => $formular,
                'isUserException' => $this->get('app.user')->getIsUserException(),
                'isDraft' => !$entity::$oneStepFormConfig,
            ));
        }

        return $this->render('document_form/config/no_config_form_uniqueness.html.twig', array(
            'formular' => $formular,
            'isUserException' => $this->get('app.user')->getIsUserException(),
        ));
    }


    /**
     * @Route("/showFormular/{slug}/{creditsUsageId}", name="formular_show")
     * @ParamConverter("formular")
     * @ParamConverter("creditsUsage", options={"id" = "creditsUsageId"})
     */
    public function showFormularAction(Request $request, Formular $formular, FormularCreditsUsage $creditsUsage)
    {

        $user = $this->getUser();

        if (null === $user) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $formularId = GeneralHelper::getServiceIdBySlug($formular->getSlug());
        $formularService = $this->get('app.formular.' . $formularId);

        if ($formularService->hasController()) {
            $controllerName = GeneralHelper::getControllerNameBySlug($formular->getSlug());
            return $this->forward("AppBundle:Formular\\" . $controllerName . ":showFormular", array(
                'request' => $request,
                'formular' => $formular,
                'creditsUsage' => $creditsUsage,
                '_route' => $request->attributes->get('_route'),
                '_route_params' => $request->attributes->get('_route_params'),
            ));
        }

        $flow = $this->get('app.form.flow.' . $formularId); // must match the flow's service id
        $flow->setId('app_form_flow_' . $formularId . '_' . $creditsUsage->getId());

        if (empty($creditsUsage->getFormularConfig()->getFormData())) {
            $formData = $formularService->applyDefaultFormData($creditsUsage, $user);
        } else {
            $formData = $this->get('jms_serializer')
                ->deserialize($creditsUsage->getFormularConfig()->getFormData(), $formularService->getEntity(), 'json');
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
            'isUserException' => $this->get('app.user')->getIsUserException(),
        ));
    }


    /**
     * @Route("/shortFormConfigurationText/{creditUsageId}", name="short_form_configuration_text")
     */
    public function getFormularDocumentsShortFormConfigurationTextAction($creditUsageId, $short = true)
    {
        $creditsUsage = $this->getDoctrine()->getManager()->getRepository('AppBundle:CreditsUsage')->find($creditUsageId);
        return new Response();

        if ($creditsUsage->getFormConfig() && $creditsUsage->getFormConfig() != 'null') {
            $formularService = $this->get('app.formular.' . $creditsUsage->getFormular()->getSlug());
            $formularService->setName($creditsUsage->getFormular()->getSlug());
            $text = $formularService->getTextForFormConfig($creditsUsage->getFormConfig(), $short);

            if ($short) {
                return new Response($this->get('translator')->trans($text['message'], $text['variables']));
            } else {
                return $this->render('document_form/config/full_configuration_text.html.twig', array(
                    'message' => $this->get('translator')->trans($text['message'], $text['variables'])
                ));
            }
        }

        return new Response();
    }



    private function handleForm($formularService, FormularCreditsUsage $creditsUsage, &$flow, &$form, &$formData)
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

            $currentStepNumber = $flow->getCurrentStepNumber();
            if ($currentStepNumber > 1) {
                $creditsUsage->setCurrentStepNumber($currentStepNumber);
            }

            $creditsUsage->getFormularConfig()->setFormData($this->get('jms_serializer')->serialize($formData, 'json'));
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

    private function generateDocument($formularService, $creditsUsage, $formData)
    {
        $filename = $formularService->getName() . $creditsUsage->getId() . '.pdf';
        $filePath = $this->getParameter('generated_documents_dir') . strtolower($formularService->getSlug()) . '/' . $filename;
        $formTemplateData = (method_exists($formularService, 'calculateExtraTemplateData')) ? $formularService->calculateExtraTemplateData($formData) : null;
        $fileBody = $this->renderView('document_pdf_template/' . strtolower($formularService->getSlug()) . ".html.twig", array(
            'data' => $formData,
            'templateData' => $formTemplateData,
            'formConfig' => json_decode($creditsUsage->getFormularConfig()->getFormConfig())
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

}