<?php

namespace AppBundle\Service\DocumentForm;

use AppBundle\Service\DocumentForm\Base\FormularGeneric;
use AppBundle\Service\DocumentForm\Base\FormularFormDefaultInterface;
use AppBundle\Service\DocumentForm\Base\FormularFormProcessInterface;

class FormularDecizieComisieCercetareAccidente extends FormularGeneric implements FormularFormDefaultInterface, FormularFormProcessInterface
{

    public function applyDefaultFormData($creditsUsage, $formData, $user)
    {
        $formData->setCompany($user->getCompany());
        $formData->setCompanyAddress($user->getAddress() . ", " . $user->getCity() . ", " . $user->getCounty());
        $creditsUsage->setFormData($this->jmsSerializer->serialize($formData, 'json'));
        $this->entityManager->flush();
    }

    public function processHandleForm($creditsUsage, $flow, &$formData)
    {
        if ($flow->getCurrentStep() == 1 && $creditsUsage->getIsFormConfigFinished()) {
            $hour = $formData->getAccidentDate()->format('H:i');
            $formData->setAccidentHour($hour);
        }
    }

    public function processEndHandleForm(&$formData)
    {

    }

}