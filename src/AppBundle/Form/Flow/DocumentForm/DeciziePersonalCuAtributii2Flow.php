<?php

namespace AppBundle\Form\Flow\DocumentForm;

use Craue\FormFlowBundle\Form\FormFlow;
use AppBundle\Form\Type\DocumentForm\DeciziePersonalCuAtributii2\DeciziePersonalCuAtributii2Type;
use Doctrine\ORM\EntityManager;

class DeciziePersonalCuAtributii2Flow extends FormFlow
{

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function loadStepsConfig()
    {
        $creditsUsageId = $this->getRequest()->get('creditsUsageId');
        $creditsUsage = $this->entityManager->getRepository('AppBundle:CreditsUsage')->find($creditsUsageId);
        if (empty($creditsUsage)) {
            throw new AccessDeniedHttpException('formular-documents.access-denied-expired');
        }

        $stepConfig = array();
        $stepConfig[] = array(
            'label' => 'Decizie Personal Cu Atributii 2',
            'type' => new DeciziePersonalCuAtributii2Type(),
        );

        return $stepConfig;
    }

    public function getName()
    {
        return 'decizie_personal_cu_atributii_2_flow';
    }

}