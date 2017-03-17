<?php

namespace AppBundle\Service\DocumentForm;

use AppBundle\Entity\CreditsUsage;
use AppBundle\Service\DocumentForm\Base\FormularGeneric;
use AppBundle\Service\DocumentForm\Base\FormularFormDefaultInterface;
use AppBundle\Service\DocumentForm\Base\FormularFormProcessInterface;

class FormularDecizieComisieCercetareAccidente extends FormularGeneric
{

    function applyDefaultFormData(CreditsUsage $creditsUsage, $user)
    {
        $entityNamespace = $this->getEntity();
        $entity = new $entityNamespace();

        $entity->setCompany($user->getProfile() ? $user->getProfile()->getCompany() : "");
        $entity->setCompanyAddress(($user->getProfile() ? $user->getProfile()->getAddress() : "") . ", " . ($user->getProfile() ? $user->getProfile()->getCity() : "") . ", " . ($user->getProfile() ? $user->getProfile()->getCounty() : ""));

        $formularConfig = $creditsUsage->getFormularConfig();
        $formularConfig->setFormData($this->jmsSerializer->serialize($entity, 'json'));

        $this->entityManager->flush();

    }

    public function processHandleForm($creditsUsage, $flow, &$formData)
    {
        if ($flow->getCurrentStep() == 1) {
            $hour = $formData->getAccidentDate()->format('H:i');
            $formData->setAccidentHour($hour);
        }
    }


    function getEntity()
    {
        return 'AppBundle\Document\DecizieComisieCercetareAccidente\DecizieComisieCercetareAccidente';
    }


    public function getName()
    {
        return 'decizie_comisie_cercetare_accidente';
    }
}