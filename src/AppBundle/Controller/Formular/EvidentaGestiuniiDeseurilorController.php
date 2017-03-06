<?php
/**
 * Created by PhpStorm.
 * User: adriancalugar
 * Date: 20/02/2017
 * Time: 18:38
 */


namespace AppBundle\Controller\Formular;

use AppBundle\Document\UniqueDocumentInterface;
use AppBundle\Entity\EgdFormularCreditsUsage;
use AppBundle\Helper\GeneralHelper;
use Application\Sonata\MediaBundle\Entity\Media;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Formular;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;


class EvidentaGestiuniiDeseurilorController extends Controller
{

    public function configFormularUniquenessAction(Formular $formular, Request $request)
    {
        $formService = $this->get('app.formular.evidenta_gestiunii_deseurilor');

        if ($formService->hasToBeUnique()) {
            $uniqueValues = $this->get('app.formular.evidenta_gestiunii_deseurilor')->getUniquenessValues($formular);

            $entity = $formService->getEntity();

            return $this->render('document_form/unique/evidenta_gestiunii_deseurilor_unique.html.twig', array(
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


    public function showFormularAction(Request $request, Formular $formular, EgdFormularCreditsUsage $creditsUsage)
    {
        $user = $this->getUser();

        if (null === $user) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $formularService = $this->get('app.formular.evidenta_gestiunii_deseurilor');

        $flow = $this->get('app.form.flow.evidenta_gestiunii_deseurilor'); // must match the flow's service id

        $flowId = 'app_form_flow_evidenta_gestiunii_deseurilor_' . $creditsUsage->getId();
        $flow->setId($flowId);
        $flow->setRequest($request);

        $formularConfig = $creditsUsage->getFormularConfig();

        if (empty($formularConfig)) {
            throw new \Exception('Form is currupted.');
        }

        if (empty($formularConfig->getFormData())) {
            $modelData = $formularService->applyDefaultFormData($creditsUsage, $user);
        } else {
            $modelData = $this->get('jms_serializer')
                ->deserialize($formularConfig->getFormData(), $formularService->getEntity(), 'json');
        }

        $flow->bind($modelData);

        // form of the current step
        $form = $flow->createForm();


        if ($flow->isValid($form)) {
            $flow->saveCurrentStepData($form);

            $currentStep = $flow->getCurrentStepNumber();
            $nextStep = false;

            $formularConfig = $creditsUsage->getFormularConfig();
            $formConfig = $formularConfig->getFormConfig();

            if ($flow->nextStep()) {
                $nextStep = $flow->getCurrentStepNumber();
            }

            $modelData = $formularService->processHandleForm($formularConfig, $modelData, $currentStep, $nextStep);

            if ($currentStep == 1) {
                if (isset($formConfig->operatia)) {
                    if ($formConfig->operatia === '3') {
                        $form->remove('operatiaDeEliminare');
                    }
                    if ($formConfig->operatia === '4') {
                        $form->remove('operatiaDeValorificare');
                    }
                }
            }

            $formularConfig->setFormData($this->get('jms_serializer')->serialize($modelData, 'json'));

            if ($nextStep && $request->get($flowId . '_transition') == 'save') {
                if ($currentStep == $formularConfig->getStep()) {
                    $flow->invalidateStepData($currentStep);
                }
                $this->getDoctrine()->getManager()->flush();

                return $this->redirect($this->generateUrl('show_valid_documents'));
            }

            if ($nextStep > $formularConfig->getStep()) {
                $formularConfig->setStep($nextStep);
            }

            $this->getDoctrine()->getManager()->flush();

            if (!$nextStep) {
                // flow finished
                $flow->reset(); // remove step data from the session

                $media = $this->generateDocument($formularService, $creditsUsage, $modelData);
                $creditsUsage->setMedia($media);
                $creditsUsage->getFormularConfig()->setIsFormConfigFinished(true);

                $this->getDoctrine()->getManager()->flush();

                $this->get('session')->getFlashBag()->set('document-generated-success', 'success.document-generated');

                return $this->redirect($this->generateUrl('show_valid_documents'));
            }

        }

        $formTemplateData = $formularService->calculateExtraTemplateData($modelData);
        $form = $flow->createForm();

        return $this->render('document_form/evidenta_gestiunii_deseurilor.html.twig', array(
            'form' => $form->createView(),
            'flow' => $flow,
            'creditsUsage' => $creditsUsage,
            'formTemplateData' => $formTemplateData,
            'isUserException' => $this->get('app.user')->getIsUserException(),
        ));
    }

    public function showDocumentsAction(Request $request)
    {
        $userId = $this->getUser()->getId();
        $creditUsageRepository = $this->getDoctrine()->getManager()->getRepository('AppBundle:EgdFormularCreditsUsage');

        $formularDocuments = $creditUsageRepository->findalluserformulardocuments($userId);

        foreach ($formularDocuments as $index => $doc) {
            $formularService = $this->get('app.formular.evidenta_gestiunii_deseurilor');

            if (method_exists($formularService, 'getTextForFormConfig') && !empty($doc['formConfig'])) {
                $text = $formularService->getTextForFormConfig($doc['formConfig'], true);

                if (!empty($doc['formConfig'])) {
                    $formConfigValues = $formularService->getValuesForFormConfig($doc['formConfig']);

                    if (isset($formConfigValues['an'])) {
                        $formularDocuments[$index]['formConfigYear'] = $formConfigValues['an'];
                    }

                    if (isset($formConfigValues['tip_deseu'])) {
                        $formularDocuments[$index]['formConfigTipDeseu'] = $formConfigValues['tip_deseu'];
                    }
                }

                $formularDocuments[$index]['currentStepNumber'] = $doc['step'];
                $formularDocuments[$index]['formConfig'] = $this->get('translator')->trans($text['message'], $text['variables']);
            }

            $formularDocuments[$index]['isDraft'] = !$doc['isFormConfigFinished'];
        }

        return $this->render('order/my_documents/order_egd_documents.html.twig', array(
                'validDocuments' => $formularDocuments,
                'isUserException' => $this->get('app.user')->getIsUserException(),
            )
        );
    }

    /**
     * @Route("/shortFormConfigurationText/{creditUsageId}", name="short_form_configuration_text")
     */
    public function getFormularDocumentsShortFormConfigurationTextAction($creditUsageId, $short = true)
    {
        $creditsUsage = $this->getDoctrine()->getManager()->getRepository('AppBundle:EgdFormularCreditsUsage')->find($creditUsageId);

        $formularService = $this->get('app.formular.evidenta_gestiunii_deseurilor');
        $text = $formularService->getTextForFormConfig($creditsUsage->getFormularConfig()->getFormConfig(), $short);

        if ($short) {
            return new Response($this->get('translator')->trans($text['message'], $text['variables']));
        }

        return $this->render('document_form/config/full_configuration_text.html.twig', array(
            'message' => $this->get('translator')->trans($text['message'], $text['variables'])
        ));
    }


    /**
     * @Route("/update-agent-quantities", name="update_agent_quantities")
     */
    public function updateAgentQuantities(Request $request)
    {
        $user = $this->getUser();
        if (null === $user) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('domain.not-logged-in'));
        }

        $creditUsageId = $request->request->get('creditUsageId');
        $creditsUsage = $this->getDoctrine()->getManager()->getRepository('AppBundle:EgdFormularCreditsUsage')->find($creditUsageId);
        if ((!$creditsUsage) || ($creditsUsage->getUser()->getId() != $user->getId())) {
            return new Response(json_encode(array(
                'success' => false,
                'message' => $this->get('translator')->trans('invalid.data')
            )));
        }

        $index = $request->request->get('indexMonth');
        $agentsQuantity = $request->request->get('agentsQuantity');

        $formularConfig = $creditsUsage->getFormularConfig();
        if (empty($formularConfig)) {
            return new Response(json_encode(array(
                'success' => false,
                'message' => $this->get('translator')->trans('invalid.data')
            )));
        }

        $formData = json_decode($formularConfig->getFormData());

        foreach ($formData->_e_g_d1_generare_deseuri[$index]->agent_economic as $key => $agent) {
            $formData->_e_g_d1_generare_deseuri[$index]->agent_economic[$key]->cantitate_deseu = $agentsQuantity[$key];
        }

        $formularConfig->setFormData(json_encode($formData));

        $em = $this->getDoctrine()->getManager();
        $em->merge($creditsUsage);
        $em->flush();

        return new Response(json_encode(array(
            'success' => true
        )));
    }

    private function generateDocument($formularService, $creditsUsage, $formData)
    {
        $filename = "EvidentaGestiuniiDeseurilor" . $creditsUsage->getId() . '.pdf';
        $filePath = $this->getParameter('generated_documents_dir')  . 'evidenta_gestiunii_deseurilor/' . $filename;
        $formTemplateData = (method_exists($formularService, 'calculateExtraTemplateData')) ? $formularService->calculateExtraTemplateData($formData) : null;
        $fileBody = $this->renderView('document_pdf_template/evidenta_gestiunii_deseurilor.html.twig', array(
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