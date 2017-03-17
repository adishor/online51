<?php

namespace AppBundle\Service\DocumentForm;

use AppBundle\Entity\CreditsUsage;
use AppBundle\Service\DocumentForm\Base\FormularGeneric;

class FormularDeciziePersonalCuAtributii2 extends FormularGeneric
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
        return 'AppBundle\Document\DeciziePersonalCuAtributii2\DeciziePersonalCuAtributii2';
    }


    public function getName()
    {
        return 'decizie_personal_cu_atributii2';
    }

}