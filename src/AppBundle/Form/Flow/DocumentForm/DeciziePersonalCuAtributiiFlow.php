<?php

namespace AppBundle\Form\Flow\DocumentForm;

use Craue\FormFlowBundle\Form\FormFlow;
use AppBundle\Form\Type\DocumentForm\DeciziePersonalCuAtributii\DeciziePersonalCuAtributiiType;
use Doctrine\ORM\EntityManager;

class DeciziePersonalCuAtributiiFlow extends FormFlow
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
            'label' => 'Decizie personal cu atributii',
            'type' => new DeciziePersonalCuAtributiiType(),
        );

        return $stepConfig;
    }

    public function getName()
    {
        return 'decizie_personal_cu_atributii_flow';
    }

}