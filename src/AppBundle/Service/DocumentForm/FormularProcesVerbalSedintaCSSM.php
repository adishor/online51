<?php

namespace AppBundle\Service\DocumentForm;

use AppBundle\Service\DocumentForm\Base\FormularGeneric;
use AppBundle\Service\DocumentForm\Base\FormularFormDefaultInterface;

class FormularProcesVerbalSedintaCSSM extends FormularGeneric implements FormularFormDefaultInterface
{

    public function applyDefaultFormData($creditsUsage, $formData, $user)
    {
        $formData->setCompany($user->getProfile() ? $user->getProfile()->getCompany() : "");
        $formData->setCompanyCity($user->getProfile() ? $user->getProfile()->getCity() : "");
        $creditsUsage->setFormData($this->jmsSerializer->serialize($formData, 'json'));
        $this->entityManager->flush();
    }

}