<?php

namespace AppBundle\Service\DocumentForm;

use AppBundle\Service\DocumentForm\Base\FormularGeneric;
use AppBundle\Service\DocumentForm\Base\FormularFormDefaultInterface;
use AppBundle\Service\DocumentForm\Base\FormularFormProcessInterface;

class FormularDecizieComisieCercetareAccidente extends FormularGeneric implements FormularFormDefaultInterface, FormularFormProcessInterface
{

    public function applyDefaultFormData($creditsUsage, $formData, $user)
    {
        $formData->setCompany($user->getProfile() ? $user->getProfile()->getCompany() : "");
        $formData->setCompanyAddress(($user->getProfile() ? $user->getProfile()->getAddress() : "") . ", " . ($user->getProfile() ? $user->getProfile()->getCity() : "") . ", " . ($user->getProfile() ? $user->getProfile()->getCounty() : ""));
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