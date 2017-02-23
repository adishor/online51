<?php
/**
 * Created by PhpStorm.
 * User: adriancalugar
 * Date: 20/02/2017
 * Time: 18:38
 */


namespace AppBundle\Controller\Formular;

use AppBundle\Document\UniqueDocumentInterface;
use AppBundle\Entity\FormularCreditsUsage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Formular;
use Symfony\Component\HttpFoundation\Request;


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


    public function showFormularAction(Request $request, Formular $formular, FormularCreditsUsage $creditsUsage)
    {
        $user = $this->getUser();

        if (null === $user) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $formularService = $this->get('app.formular.evidenta_gestiunii_deseurilor');

        $flow = $this->get('app.form.flow.evidenta_gestiunii_deseurilor'); // must match the flow's service id
        $flow->setId('app_form_flow_evidenta_gestiunii_deseurilor_' . $creditsUsage->getId());
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

            if ($flow->nextStep()) {
                $nextStep = $flow->getCurrentStepNumber();
            }

            $formularConfig = $creditsUsage->getFormularConfig();
            $formConfig = $formularConfig->getFormConfig();

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

            $creditsUsage->setCurrentStepNumber($nextStep);

            $formularConfig->setFormData($this->get('jms_serializer')->serialize($modelData, 'json'));

            $this->getDoctrine()->getManager()->flush();

            if (!$nextStep) {
                // flow finished
                $flow->reset(); // remove step data from the session

                $media = $this->generateDocument($formularService, $creditsUsage, $modelData);
                $creditsUsage->setMedia($media);
                $this->getDoctrine()->getManager()->flush();

                $this->get('session')->getFlashBag()->set('document-generated-success', 'success.document-generated');

                return $this->redirect($this->generateUrl('show_valid_documents') . '?mediaId=' . $media->getId());
            }

        }

        $formTemplateData = $formularService->calculateExtraTemplateData($modelData);
        $form = $flow->createForm();

        return $this->render('document_form/' . strtolower($formular->getSlug()) . ".html.twig", array(
            'form' => $form->createView(),
            'flow' => $flow,
            'creditsUsage' => $creditsUsage,
            'formTemplateData' => $formTemplateData,
            'isUserException' => $this->get('app.user')->getIsUserException(),
        ));
    }

}