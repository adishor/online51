<?php

namespace AppBundle\Service\DocumentForm;

use AppBundle\Entity\CreditsUsage;
use AppBundle\Service\DocumentForm\Base\FormularGeneric;

class FormularProcesVerbalSedintaCSSM extends FormularGeneric
{

    function applyDefaultFormData(CreditsUsage $creditsUsage, $user)
    {
        $entityNamespace = $this->getEntity();
        $entity = new $entityNamespace();

        $entity->setCompany($user->getProfile() ? $user->getProfile()->getCompany() : "");
        $entity->setCompanyCity($user->getProfile() ? $user->getProfile()->getCity() : "");

        $formularConfig = $creditsUsage->getFormularConfig();
        $formularConfig->setFormData($this->jmsSerializer->serialize($entity, 'json'));

        $this->entityManager->flush();

    }

    function getEntity()
    {
        return 'AppBundle\Document\ProcesVerbalSedintaCSSM\ProcesVerbalSedintaCSSM';
    }


    public function getName()
    {
        return 'proces_verbal_sedinta_c_s_s_m';
    }
}