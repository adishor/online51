<?php

namespace AppBundle\Service\DocumentForm;

use AppBundle\Entity\CreditsUsage;
use AppBundle\Service\DocumentForm\Base\FormularGeneric;
use AppBundle\Service\DocumentForm\Base\FormularFormConfigTextInterface;
use AppBundle\Service\DocumentForm\Base\FormularFormDefaultInterface;
use AppBundle\Service\DocumentForm\Base\FormularFormProcessInterface;

class FormularConvocatorCSSMService extends FormularGeneric
{

    public function getTextForFormConfig($formConfig, $short = false)
    {

        $formConfigD = json_decode($formConfig);

        return ($formConfigD) ? array(
            'message' => 'document-form.text.ccssm',
            'variables' => array(
                'day' => $formConfigD->data,
                'hour' => $formConfigD->ora,
            )) : NULL;
    }

    public function applyDefaultFormData(CreditsUsage $creditsUsage, $user)
    {
        $entityNamespace = $this->getEntity();
        $entity = new $entityNamespace();

        $entity->setCompany($user->getProfile() ? $user->getProfile()->getCompany() : "");
        $entity->setCompanyCity($user->getProfile()->getCity() ? $user->getProfile()->getCity()->getId() : NULL);
        $entity->setCompanyCounty($user->getProfile()->getCounty() ? $user->getProfile()->getCounty()->getId() : NULL);

        $formularConfig = $creditsUsage->getFormularConfig();
        $formularConfig->setFormData($this->jmsSerializer->serialize($entity, 'json'));

        $this->entityManager->flush();
    }

    public function processHandleForm($creditsUsage, $flow, &$formData)
    {
        if ($flow->getCurrentStep() == 1) {
            $hour = $formData->getMeetingDate()->format('H:i');

            $formData->setCompanyCounty($formData->getCompanyCounty()->getId());
            $formData->setCompanyCity($formData->getCompanyCity()->getId());

            $formConfig['data'] = $formData->getMeetingDate()->format('d/m/Y');
            $formConfig['ora'] = $hour;
            $creditsUsage->setFormConfig(json_encode($formConfig));

            $formData->setMeetingHour($hour);
        }
    }

    public function processEndHandleForm(&$formData)
    {

    }

    function getEntity()
    {
        return 'AppBundle\Document\ConvocatorCSSM\ConvocatorCSSM';
    }
}