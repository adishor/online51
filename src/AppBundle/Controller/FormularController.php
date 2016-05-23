<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Formular;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Application\Sonata\MediaBundle\Entity\Media;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Filesystem\Filesystem;

class FormularController extends Controller
{

    /**
     * @Route("/showFormular/{slug}/{hash}", name="formular_show")
     * @ParamConverter("formular")
     */
    public function showFormularAction(Request $request, Formular $formular, $hash)
    {

        $user = $this->getUser();
        if (null === $user) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('domain.not-logged-in'));
        }

        $creditsUsage = $this->getDoctrine()->getManager()->getRepository('AppBundle:CreditsUsage')
          ->findOneByFormHashNotExpired($hash);
        if (empty($creditsUsage)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('formular-documents.access-denied-expired'));
        }

        $creditsUsage = reset($creditsUsage);
        $name = str_replace("_", "", $formular->getSlug());
        $entity = "AppBundle\\Entity\\DocumentForm\\" . $name;
        $type = "AppBundle\\Form\\Type\\DocumentForm\\" . $name . "Type";
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
        $form = $this->createForm(new $type(), $formData);

        $this->$applyFormCustomizationMethod($form, $creditsUsage);

        $location = $this->$handleFormMethod($creditsUsage, $name, $form, $request, $formData, $formular->getSlug());

        if (is_array($location)) {

            return $this->redirect($this->generateUrl('formular_documents_show') . '?mediaId=' . $location['mediaId']);
        }

        $formTemplateData = $this->$calculateExtraTemplateDataMethod($formData);

        return $this->render('document_form/' . strtolower($formular->getSlug()) . ".html.twig", array(
              'form' => $form->createView(),
              'formTemplateData' => $formTemplateData,
              'location' => $location,
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
              'isUserException' => $this->get('app.user_helper')->getIsUserException()
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
        }

        return $this->render('document_form/show_formular_documents.html.twig', array(
              'formularDocuments' => $formularDocuments
        ));
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

    public function applyFormCustomizationEvidentaGestiuniiDeseurilor($form, $creditsUsage)
    {

        $formConfig = json_decode($creditsUsage->getFormConfig());
        if ($formConfig->operatia === '3') {
            $form->remove('EGD4EliminareDeseuri');
            $form->remove('save5');
        }
        if ($formConfig->operatia === '4') {
            $form->remove('EGD3ValorificareDeseuri');
            $form->remove('save4');
        }
    }

    public function handleFormEvidentaGestiuniiDeseurilor($creditsUsage, $name, $form, $request, $formData, $slug)
    {

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('generateDocument')->isClicked()) {
                $calculateExtraTemplateDataMethod = 'calculateExtraTemplateData' . $name;
                $formTemplateData = $this->$calculateExtraTemplateDataMethod($formData);
                $generateDocumentTemplate = 'document_pdf_template/' . strtolower($slug) . ".html.twig";
                $generateDocumentDirectory = $this->getParameter('generated_documents_dir') . strtolower($slug) . '/';
                $media = $this->generateDocument($name, $creditsUsage, $generateDocumentDirectory, $generateDocumentTemplate, $formData, $formTemplateData);
                $creditsUsage->setMedia($media);
                $creditsUsage->setFormData($this->get('jms_serializer')->serialize($formData, 'json'));
                $this->getDoctrine()->getManager()->flush();

                $this->get('session')->getFlashBag()->set('document-generated-success', 'success.document-generated');

                return array('mediaId' => $media->getId());
            }
            $creditsUsage->setFormData($this->get('jms_serializer')->serialize($formData, 'json'));
            $this->getDoctrine()->getManager()->flush();

            $location = 'formtab';
            if ($form->get('save1')->isClicked()) {
                $location = 'formtab1';
            }
            if ($form->get('save2')->isClicked()) {
                $location = 'formtab2';
            }
            if ($form->get('save3')->isClicked()) {
                $formConfig = json_decode($creditsUsage->getFormConfig());
                $location = $formConfig->operatia === '3' ? 'formtab3' : 'formtab4';
            }

            $this->get('session')->getFlashBag()->set('form-success', 'success.form-saved');

            return $location;
        }

        if ($form->get('generateDocument')->isClicked()) {

            $this->get('session')->getFlashBag()->set('form-error', 'document-form.error.egd.form-general-error');

            return 'formtab';
        }

        return 'formtab';
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

}