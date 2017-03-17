<?php

namespace AppBundle\Service\DocumentForm;

use AppBundle\Entity\CreditsUsage;
use AppBundle\Service\DocumentForm\Base\FormularGeneric;
use AppBundle\Service\DocumentForm\Base\FormularFormDefaultInterface;

class FormularProcesVerbalControl extends FormularGeneric
{

    function applyDefaultFormData(CreditsUsage $creditsUsage, $user)
    {
        $entityNamespace = $this->getEntity();
        $entity = new $entityNamespace();

        $entity->setCompany($user->getProfile() ? $user->getProfile()->getCompany() : "");

        $formularConfig = $creditsUsage->getFormularConfig();
        $formularConfig->setFormData($this->jmsSerializer->serialize($entity, 'json'));

        $this->entityManager->flush();

    }

    function getEntity()
    {
        return 'AppBundle\Document\ProcesVerbalControl\ProcesVerbalControl';
    }


    public function getName()
    {
        return 'proces_verbal_control';
    }

}