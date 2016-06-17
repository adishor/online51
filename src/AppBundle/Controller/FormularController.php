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
        if (null === $user) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('domain.not-logged-in'));
        }

        $name = str_replace("_", "", $formular->getSlug());
        $entity = "AppBundle\\Entity\\DocumentForm\\" . $name;
        $handleFormMethod = 'handleForm' . $name;
        $applyUniqueConfigurationMethod = 'applyUniqueConfiguration' . $name;
        $applyFormCustomizationMethod = 'applyFormCustomization' . $name;

        $calculateExtraTemplateDataMethod = 'calculateExtraTemplateData' . $name;

        if (empty($creditsUsage->getFormData())) {
            $formData = new $entity();
            $this->$applyUniqueConfigurationMethod($creditsUsage, $formData, $user->getCompany());
        } else {
            $formData = $this->get('jms_serializer')->deserialize($creditsUsage->getFormData(), $entity, 'json');
        }

        $flow = $this->get('app.form.flow.egd'); // must match the flow's service id
        if ($request->getMethod() == 'GET' && null === $request->query->get('step')) {
            $flow->reset();
        }
        $flow->bind($formData);

        // form of the current step
        $form = $flow->createForm();
        $this->$applyFormCustomizationMethod($flow, $form, $creditsUsage);
        $response = $this->$handleFormMethod($creditsUsage, $name, $flow, $form, $formData, $formular->getSlug());
        if ($response) {
            return $this->redirect($this->generateUrl('formular_documents_show') . '?mediaId=' . $response);
        }

        $formTemplateData = $this->$calculateExtraTemplateDataMethod($formData);

        return $this->render('document_form/' . strtolower($formular->getSlug()) . ".html.twig", array(
              'form' => $form->createView(),
              'flow' => $flow,
              'creditsUsage' => $creditsUsage,
              'formTemplateData' => $formTemplateData,
              'isUserException' => $this->get('app.user_helper')->getIsUserException(),
            )
        );
    }

    /**
     * @Route("/configFormular/{slug}", name="formular_config")
     * @ParamConverter("formular")
     */
    public function configFormularUniquenessAction(Formular $formular, Request $request)
    {
        $name = str_replace("_", "", $formular->getSlug());
        $entity = "AppBundle\\Entity\\DocumentForm\\" . $name;

        $uniqueValues = [];
        if (isset($entity::$uniqueness)) {
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
        }

        return $this->render('document_form/config_form_uniqueness.html.twig', array(
              'uniqueValues' => $uniqueValues,
              'formular' => $formular,
              'isUserException' => $this->get('app.user_helper')->getIsUserException(),
              'isDraft' => !$entity::$oneStepFormConfig,
        ));
    }

    /**
     * @Route("/myFormularDocuments", name="formular_documents_show")
     */
    public function showFormularDocumentsAction(Request $request)
    {
        $user = $this->getUser();
        if (null === $user) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('domain.not-logged-in'));
        }

        $formularDocuments = $this->getDoctrine()->getManager()->getRepository('AppBundle:CreditsUsage')
          ->findAllUserFormularDocuments($user->getId(), ($request->query->has('mediaId')) ? $request->query->get('mediaId') : null );

        foreach ($formularDocuments as $index => $doc) {
            $name = str_replace("_", "", $doc['fslug']);
            $getValuesForFormConfigOptions = 'getValuesForFormConfigOptions' . $name;
            $formularDocuments[$index]['formConfig'] = $this->$getValuesForFormConfigOptions($doc['formConfig']);
            $formularDocuments[$index]['isDraft'] = !$doc['isFormConfigFinished'];
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($formularDocuments, $request->query->getInt('page', 1), $this->getParameter('pagination')['formularDocuments']);

        return $this->render('document_form/show_formular_documents.html.twig', array(
              'pagination' => $pagination,
              'isUserException' => $this->get('app.user_helper')->getIsUserException(),
        ));
    }

    /**
     * @Route("/shortFormConfigurationText/{creditUsageId}", name="short_form_configuration_text")
     */
    public function getFormularDocumentsShortFormConfigurationTextAction($creditUsageId)
    {
        $creditsUsage = $this->getDoctrine()->getManager()->getRepository('AppBundle:CreditsUsage')->find($creditUsageId);

        $name = str_replace("_", "", $creditsUsage->getFormular()->getSlug());
        $function = "getFormText" . $name;

        return new Response($this->get('app.formular_helper')->$function($creditsUsage->getFormConfig(), true));
    }

    public function generateDocument($name, $creditsUsage, $fileDirectory, $template, $formData, $formTemplateData)
    {
        $filename = $name . $creditsUsage->getId() . '.pdf';
        $filePath = $fileDirectory . $filename;
        $fileBody = $this->renderView($template, array('data' => $formData, 'templateData' => $formTemplateData, 'formConfig' => json_decode($creditsUsage->getFormConfig())));
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

    public function applyUniqueConfigurationEvidentaGestiuniiDeseurilor($creditsUsage, $formData, $userCompany)
    {
        $formConfigValue = $this->getValuesForFormConfigOptionsEvidentaGestiuniiDeseurilor($creditsUsage->getFormConfig());

        $formData->setAgentEconomic($userCompany);
        $formData->setAn($formConfigValue['an']);
        $formData->setTipDeseu($formConfigValue['tip_deseu']);
        $formData->setTipDeseuCod($formConfigValue['tip_deseu_cod']);
        $creditsUsage->setFormData($this->get('jms_serializer')->serialize($formData, 'json'));
        $this->getDoctrine()->getManager()->flush();
    }

    public function applyFormCustomizationEvidentaGestiuniiDeseurilor($flow, $form, $creditsUsage)
    {
        if ($flow->getCurrentStep() == 1) {
            $formConfig = json_decode($creditsUsage->getFormConfig());
            if (isset($formConfig->operatia)) {
                if ($formConfig->operatia === '3') {
                    $form->remove('operatiaDeEliminare');
                }
                if ($formConfig->operatia === '4') {
                    $form->remove('operatiaDeValorificare');
                }
            }
        }
    }

    public function handleFormEvidentaGestiuniiDeseurilor($creditsUsage, $name, &$flow, &$form, $formData, $slug)
    {
        if ($flow->isValid($form)) {
            $flow->saveCurrentStepData($form);

            if ($flow->getCurrentStep() == 1 && $creditsUsage->getIsFormConfigFinished()) {
                $formConfig = $this->getValuesForFormConfigOptionsEvidentaGestiuniiDeseurilor($creditsUsage->getFormConfig());
                $formConfig['tip_deseu'] = $formConfig['tip_deseu_cod'];
                unset($formConfig['tip_deseu_cod']);
                $formConfig['operatia'] = $formData->getOperatia();
                $creditsUsage->setFormConfig(json_encode($formConfig));

                foreach ($formData->getEGD2StocareTratareTransportDeseuri() as $key => $item) {
                    $item->setTratareScop(str_replace(array(3, 4), array('V', 'E'), $formData->getOperatia()));
                    $formData->getEGD2StocareTratareTransportDeseuri()[$key] = $item;
                }

                if ($formData->getOperatia() == 3) {
                    foreach ($formData->getEGD1GenerareDeseuri() as $key => $item) {
                        $item->setCantitateDeseuEliminata(0);
                        $formData->getEGD1GenerareDeseuri()[$key] = $item;
                    }
                }
                if ($formData->getOperatia() == 4) {
                    foreach ($formData->getEGD1GenerareDeseuri() as $key => $item) {
                        $item->setCantitateDeseuValorificata(0);
                        $formData->getEGD1GenerareDeseuri()[$key] = $item;
                    }
                }
            }

            if ($flow->getCurrentStep() == ($creditsUsage->getIsFormConfigFinished() ? 2 : 1)) {
                foreach ($formData->getEGD2StocareTratareTransportDeseuri() as $key => $item) {
                    $item->setStocareTip($formData->getStocareTip());
                    $item->setTratareMod($formData->getTratareMod());
                    $item->setTratareScop($formData->getTratareScop());
                    $item->setTransportMijloc($formData->getTransportMijloc());
                    $item->setTransportDestinatie($formData->getTransportDestinatie());
                    $formData->getEGD2StocareTratareTransportDeseuri()[$key] = $item;
                }

                if ($formData->getOperatiaDeValorificare()) {
                    foreach ($formData->getEGD3ValorificareDeseuri() as $key => $item) {
                        $item->setOperatiaDeValorificare($formData->getOperatiaDeValorificare());
                        $item->setAgentEconomicValorificare(NULL);
                        foreach ($formData->getEGDCompany() as $company) {
                            if ($key + 1 >= $company->getStartMonth()) {
                                $item->setAgentEconomicValorificare($company->getName());
                            }
                        }
                        $formData->getEGD3ValorificareDeseuri()[$key] = $item;
                    }
                }

                if ($formData->getOperatiaDeEliminare()) {
                    foreach ($formData->getEGD4EliminareDeseuri() as $key => $item) {
                        $item->setOperatiaDeEliminare($formData->getOperatiaDeEliminare());
                        $item->setAgentEconomicEliminare(NULL);
                        foreach ($formData->getEGDCompany() as $company) {
                            if ($key + 1 >= $company->getStartMonth()) {
                                $item->setAgentEconomicEliminare($company->getName());
                            }
                        }
                        $formData->getEGD4EliminareDeseuri()[$key] = $item;
                    }
                }
            }

            $creditsUsage->setFormData($this->get('jms_serializer')->serialize($formData, 'json'));
            $this->getDoctrine()->getManager()->flush();

            if ($flow->nextStep()) {
                // form for the next step
                $form = $flow->createForm();
                $this->get('session')->getFlashBag()->set('form-success', 'success.form-saved');
            } else {
                // flow finished
                $flow->reset(); // remove step data from the session

                $calculateExtraTemplateDataMethod = 'calculateExtraTemplateData' . $name;
                $formTemplateData = $this->$calculateExtraTemplateDataMethod($formData);
                $generateDocumentTemplate = 'document_pdf_template/' . strtolower($slug) . ".html.twig";
                $generateDocumentDirectory = $this->getParameter('generated_documents_dir') . strtolower($slug) . '/';
                $media = $this->generateDocument($name, $creditsUsage, $generateDocumentDirectory, $generateDocumentTemplate, $formData, $formTemplateData);
                $creditsUsage->setMedia($media);
                $this->getDoctrine()->getManager()->flush();

                $this->get('session')->getFlashBag()->set('document-generated-success', 'success.document-generated');

                return $media->getId();
            }

            return false;
        }
    }

    public function calculateExtraTemplateDataEvidentaGestiuniiDeseurilor($formData)
    {
        $formTemplateData = array();

        $EGDTotals = $this->get('app.formular_helper')->CalculateEvidentaGestiuniiDeseurilorTotals($formData);
        $formTemplateData['EGDTotals'] = $EGDTotals;

        return $formTemplateData;
    }

    public function getValuesForFormConfigOptionsEvidentaGestiuniiDeseurilor($formConfig)
    {
        $formConfigValue = [];

        $formConfig = json_decode($formConfig);
        foreach ($formConfig as $key => $config) {
            switch ($key) {
                case 'an':
                    $formConfigValue[$key] = $config;
                    break;
                case 'tip_deseu':
                    $deseuCodes = explode(" ", $config);

                    $tipDeseuArray = $this->getParameter('tip_deseu');
                    $formConfigValue[$key] = $tipDeseuArray[$deseuCodes[0]]['name'] . "; " .
                      $tipDeseuArray[$deseuCodes[0]]['values'][$deseuCodes[1]]['name'] . "; " .
                      $tipDeseuArray[$deseuCodes[0]]['values'][$deseuCodes[1]]['values'][$deseuCodes[2]];
                    $formConfigValue[$key . "_cod"] = $config;
                    break;
                case 'operatia':
                    $formConfigValue[$key] = $this->getParameter('operatia')[$config];
                    break;
            }
        }

        return $formConfigValue;
    }

    /**
     * @Route("/unique_configuration_on_form_egd", name="unique_configuration_on_form_egd")
     */
    public function applyUniqueConfigurationOnFormEvidentaGestiuniiDeseurilor(Request $request)
    {
        $configOperatia = $request->request->get('configOperatia');
        if ($configOperatia > 0) {
            $user = $this->getUser();
            $userHelper = $this->get('app.user_helper');

            $creditsUsageId = $request->request->get('creditUsageId');
            $creditsUsage = $this->getDoctrine()->getManager()->getRepository('AppBundle:CreditsUsage')->find($creditsUsageId);

            $formConfig = $this->getValuesForFormConfigOptionsEvidentaGestiuniiDeseurilor($creditsUsage->getFormConfig());
            $formConfig['tip_deseu'] = $formConfig['tip_deseu_cod'];
            unset($formConfig['tip_deseu_cod']);
            $formConfig['operatia'] = $configOperatia;
            $formHash = md5(json_encode($this->getUser()->getId()) . json_encode($formConfig));

            if (true === $userHelper->isValidUserFormular($user->getId(), $creditsUsage->getFormular()->getId(), $formConfig)) {
                if (!$userHelper->getIsUserException()) {
                    $this->get('session')->getFlashBag()->add('formular-info', 'domain.formular.already-unlocked');
                    $this->get('session')->getFlashBag()->add('form-error', 'domain.formular.no-credits-used');
                }
                $response = new Response(json_encode(array(
                      'success' => true,
                      'credits' => $user->getCreditsTotal(),
                      'formHash' => $formHash
                )));

                return $response;
            }
            if (!$userHelper->getIsUserException()) {
                if (($user->getCreditsTotal() - $creditsUsage->getCredit() < 0) || (null === $user->getCreditsTotal())) {
                    $response = new Response(json_encode(array(
                          'success' => false,
                          'message' => $this->get('translator')->trans('domain.formular.no-credits')
                    )));

                    return $response;
                }
            }

            $userHelper->updateValidUserCredits();

            $creditsUsage->setFormConfig(json_encode($formConfig));
            $creditsUsage->setIsFormConfigFinished(TRUE);
            $creditsUsage->setFormHash($formHash);
            $user->setCreditsTotal($user->getCreditsTotal() - $creditsUsage->getCredit());
            $user->setLastCreditUpdate(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            $response = new Response(json_encode(array(
                  'success' => true,
                  'credits' => $user->getCreditsTotal(),
                  'formHash' => $formHash
            )));

            return $response;
        }

        return new Response(json_encode(array(
              'success' => false,
              'message' => $this->get('translator')->trans('modal.config-form-add-uniqueness-on-form.error')
          )), 200);
    }

}