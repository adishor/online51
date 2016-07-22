<?php

namespace AppBundle\Service\DocumentForm;

use AppBundle\Service\DocumentForm\Base\FormularGeneric;
use AppBundle\Service\DocumentForm\Base\FormularFormDefaultInterface;

class FormularPermisDeLucruCuFoc extends FormularGeneric implements FormularFormDefaultInterface
{

    public function applyDefaultFormData($creditsUsage, $formData, $user)
    {
        $formData->setCompany($user->getProfile()->getCompany());
        $creditsUsage->setFormData($this->jmsSerializer->serialize($formData, 'json'));
        $this->entityManager->flush();
    }

}