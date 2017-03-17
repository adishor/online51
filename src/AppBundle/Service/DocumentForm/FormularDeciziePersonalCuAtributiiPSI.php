<?php

namespace AppBundle\Service\DocumentForm;

use AppBundle\Entity\CreditsUsage;
use AppBundle\Service\DocumentForm\Base\FormularGeneric;

class FormularDeciziePersonalCuAtributiiPSI extends FormularGeneric
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
        return 'AppBundle\Document\DeciziePersonalCuAtributiiPSI\DeciziePersonalCuAtributiiPSI';
    }


    public function getName()
    {
        return 'decizie_personal_cu_atributii_p_s_i';
    }

}