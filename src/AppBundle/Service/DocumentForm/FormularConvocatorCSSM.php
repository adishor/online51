<?php

namespace AppBundle\Service\DocumentForm;

use AppBundle\Service\DocumentForm\Base\FormularGeneric;
use AppBundle\Service\DocumentForm\Base\FormularFormConfigTextInterface;
use AppBundle\Service\DocumentForm\Base\FormularFormDefaultInterface;
use AppBundle\Service\DocumentForm\Base\FormularFormProcessInterface;

class FormularConvocatorCSSM extends FormularGeneric implements FormularFormConfigTextInterface, FormularFormDefaultInterface, FormularFormProcessInterface
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

    public function applyDefaultFormData($creditsUsage, $formData, $user)
    {
        $formData->setCompany($user->getProfile() ? $user->getProfile()->getCompany() : "");
        $formData->setCompanyCity($user->getProfile()->getCity() ? $user->getProfile()->getCity()->getId() : NULL);
        $formData->setCompanyCounty($user->getProfile()->getCounty() ? $user->getProfile()->getCounty()->getId() : NULL);
        $creditsUsage->setFormData($this->jmsSerializer->serialize($formData, 'json'));
        $this->entityManager->flush();
    }

    public function processHandleForm($creditsUsage, $flow, &$formData)
    {
        if ($flow->getCurrentStep() == 1 && $creditsUsage->getIsFormConfigFinished()) {
            $hour = $formData->getMeetingDate()->format('H:i');

            $formConfig['data'] = $formData->getMeetingDate()->format('d/m/Y');
            $formConfig['ora'] = $hour;
            $creditsUsage->setFormConfig(json_encode($formConfig));

            $formData->setMeetingHour($hour);
        }
    }

    public function processEndHandleForm(&$formData)
    {

    }

}