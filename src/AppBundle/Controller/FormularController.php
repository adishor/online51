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
        $handleMethod = 'handle' . $name;
        $applyUniqueConfigurationMethod = 'applyUniqueConfiguration' . $name;
        $generateDocumentTemplate = 'document_pdf_template/' . strtolower($formular->getSlug()) . ".html.twig";
        $generateDocumentDirectory = $this->getParameter('generated_documents_dir') . strtolower($formular->getSlug()) . '/';
        $entityManager = $this->getDoctrine()->getManager();
        $creditsUsage = $entityManager->getRepository('AppBundle:CreditsUsage')->findOneByFormHash($hash);
        $serializer = $this->get('jms_serializer');
        if (empty($creditsUsage->getFormData())) {
            $formData = new $entity();
            $this->$applyUniqueConfigurationMethod($serializer, $entityManager, $creditsUsage, $formData, $user->getCompany());
        } else {
            $formData = $serializer->deserialize($creditsUsage->getFormData(), $entity, 'json');
        }
        $form = $this->createForm(new $type(), $formData);

        $this->$handleMethod($serializer, $entityManager, $creditsUsage, $name, $form, $request, $formData, $generateDocumentDirectory, $generateDocumentTemplate);

        return $this->render('document_form/' . strtolower($formular->getSlug()) . ".html.twig", array(
              'form' => $form->createView()
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

    public function applyUniqueConfigurationEvidentaGestiuniiDeseurilor($serializer, $entityManager, $creditsUsage, $formData, $userCompany)
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
        $creditsUsage->setFormData($serializer->serialize($formData, 'json'));
        $entityManager->flush();
    }

    public function handleEvidentaGestiuniiDeseurilor($serializer, $entityManager, $creditsUsage, $name, $form, $request, $formData, $generateDocumentDirectory, $generateDocumentTemplate)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('generateDocumentClicked')->getData() === 'true') {
                $media = $this->generateDocument($name . $creditsUsage->getId(), $generateDocumentDirectory, $generateDocumentTemplate, $formData);
                $creditsUsage->setMedia($media);
                //lock document generation
            }
            $creditsUsage->setFormData($serializer->serialize($formData, 'json'));
            $entityManager->flush();
        }
    }

    public function generateDocument($filename, $fileDirectory, $template, $templateData)
    {
        $filePath = $fileDirectory . $filename;
        $fileBody = $this->render($template, array('data' => $templateData));
        $this->get('knp_snappy.pdf')->generateFromHtml($fileBody, $filePath);

        $file = new UploadedFile($filePath, $filename);
        $media = new Media();
        $media->setBinaryContent($file);
        $media->setName($filename);
        $media->setProviderName('sonata.media.provider.file');
        $media->setContext('default');
        $media->setMediaType($media::FORM_GENERATED_TYPE);

        return $media;
    }

}