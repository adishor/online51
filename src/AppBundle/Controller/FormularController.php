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
    public function showFormularAction(Formular $formular, $hash, Request $request)
    {
        //check if document is already unlocked or valid for user
        $user = $this->getUser();
        if (null === $user) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('domain.not-logged-in'));
        }

        $name = str_replace("_", "", $formular->getSlug());
        $entity = "AppBundle\\Entity\\DocumentForm\\" . $name;
        $type = "AppBundle\\Form\\Type\\DocumentForm\\" . $name . "Type";
        $handleFormMethod = 'handleForm' . $name;
        $applyUniqueConfigurationMethod = 'applyUniqueConfiguration' . $name;
        $applyFormCustomizationMethod = 'applyFormCustomization' . $name;
        $calculateExtraTemplateDataMethod = 'calculateExtraTemplateData' . $name;

        $creditsUsage = $this->getDoctrine()->getManager()->getRepository('AppBundle:CreditsUsage')->findOneByFormHash($hash);

        if (empty($creditsUsage->getFormData())) {
            $formData = new $entity();
            $this->$applyUniqueConfigurationMethod($creditsUsage, $formData, $user->getCompany());
        } else {
            $formData = $this->get('jms_serializer')->deserialize($creditsUsage->getFormData(), $entity, 'json');
        }
        $form = $this->createForm(new $type(), $formData);

        $this->$applyFormCustomizationMethod($form, $creditsUsage);

        $this->$handleFormMethod($creditsUsage, $name, $form, $request, $formData, $formular->getSlug());

        $formTemplateData = $this->$calculateExtraTemplateDataMethod($formData);


        return $this->render('document_form/' . strtolower($formular->getSlug()) . ".html.twig", array(
              'form' => $form->createView(),
              'formTemplateData' => $formTemplateData,
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

    public function applyUniqueConfigurationEvidentaGestiuniiDeseurilor($creditsUsage, $formData, $userCompany)
    {
        $formConfig = json_decode($creditsUsage->getFormConfig());

        $deseuCodes = explode(" ", $formConfig->tip_deseu);

        $tipDeseuArray = $this->getParameter('tip_deseu');
        $tipDeseu = $tipDeseuArray[$deseuCodes[0]]['name'] . "; " .
          $tipDeseuArray[$deseuCodes[0]]['values'][$deseuCodes[1]]['name'] . "; " .
          $tipDeseuArray[$deseuCodes[0]]['values'][$deseuCodes[1]]['values'][$deseuCodes[2]];

        $formData->setAgentEconomic($userCompany);
        $formData->setAn($formConfig->an);
        $formData->setTipDeseu($tipDeseu);
        $formData->setTipDeseuCod($formConfig->tip_deseu);
        $creditsUsage->setFormData($this->get('jms_serializer')->serialize($formData, 'json'));
        $this->getDoctrine()->getManager()->flush();
    }

    public function applyFormCustomizationEvidentaGestiuniiDeseurilor($form, $creditsUsage)
    {

        $formConfig = json_decode($creditsUsage->getFormConfig());
        if ($formConfig->operatia === '3') {
            $form->remove('EGD3ValorificareDeseuri');
            $form->remove('save4');
        }
        if ($formConfig->operatia === '4') {
            $form->remove('EGD4EliminareDeseuri');
            $form->remove('save5');
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
                //lock document generation
            }
            $creditsUsage->setFormData($this->get('jms_serializer')->serialize($formData, 'json'));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('formular_show', array('hash' => $creditsUsage->getFormHash(), 'slug' => $slug));
        }
    }

    public function calculateExtraTemplateDataEvidentaGestiuniiDeseurilor($formData)
    {
        $formTemplateData = array();

        $EGDTotals = $this->get('app.formular_helper')->CalculateEvidentaGestiuniiDeseurilorTotals($formData);
        $formTemplateData['EGDTotals'] = $EGDTotals;

        return $formTemplateData;
    }

    public function generateDocument($name, $creditsUsage, $fileDirectory, $template, $formData, $formTemplateData)
    {
        $filename = $name . $creditsUsage->getId() . '.pdf';
        $filePath = $fileDirectory . $filename;
        $fileBody = $this->render($template, array('data' => $formData, 'formTemplateData' => $formTemplateData));
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