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
     */
    public function showFormularAction(Request $request, Formular $formular, $creditsUsageId)
    {

        $user = $this->getUser();

        if (null === $user) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $formularId = GeneralHelper::getServiceIdBySlug($formular->getSlug());
        $formularService = $this->get('app.formular.' . $formularId);

        if ($formularService->hasController()) {
            $controllerName = GeneralHelper::getControllerNameBySlug($formular->getSlug());
            $creditsUsage = $this->getDoctrine()->getManager()->getRepository('AppBundle:EgdFormularCreditsUsage')->find($creditsUsageId);
            return $this->forward("AppBundle:Formular\\" . $controllerName . ":showFormular", array(
                'request' => $request,
                'formular' => $formular,
                'creditsUsage' => $creditsUsage,
                '_route' => $request->attributes->get('_route'),
                '_route_params' => $request->attributes->get('_route_params'),
            ));
        }

        $creditsUsage = $this->getDoctrine()->getManager()->getRepository('AppBundle:FormularCreditsUsage')->find($creditsUsageId);
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

        $response = $this->handleForm($request, $formularService, $creditsUsage, $formularId, $flow, $form, $formData);

        if ($response) {
            return $this->redirect($this->generateUrl('show_valid_documents', array('type' => 'forms')));
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


    private function handleForm(Request $request, $formularService, FormularCreditsUsage $creditsUsage, $formularId, &$flow, &$form, &$formData)
    {
        if ($request->isMethod('POST')) {

            $creditsUsage->getFormularConfig()->setFormData($this->get('jms_serializer')->serialize($formData, 'json'));
            $this->getDoctrine()->getManager()->flush();

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


                if ($nextStep || $this->get('request')->request->has('btnSave')) {
                    // form for the next step
                    $form = $flow->createForm();
                } else {
                    // flow finished
                    $flow->reset(); // remove step data from the session

                    $media = $this->generateDocument($formularId, $creditsUsage, $formData, $formularId);
                    $creditsUsage->setMedia($media);
                    $creditsUsage->getFormularConfig()->setIsFormConfigFinished(true);

                    $this->getDoctrine()->getManager()->flush();

                    return true;
                }
            }
        }

        return false;
    }

    private function generateDocument($formularService, $creditsUsage, $formData, $formularId)
    {
        $filename = $formularId . $creditsUsage->getId() . '.pdf';
        $filePath = $this->getParameter('generated_documents_dir') . $formularId . '/' . $filename;
        $formTemplateData = (method_exists($formularService, 'calculateExtraTemplateData')) ? $formularService->calculateExtraTemplateData($formData) : null;
        $fileBody = $this->renderView('document_pdf_template/' . $formularId . ".html.twig", array(
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
