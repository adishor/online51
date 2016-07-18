<?php

namespace AppBundle\Form\Flow\DocumentForm;

use Craue\FormFlowBundle\Form\FormFlow;
use AppBundle\Form\Type\DocumentForm\PermisDeLucruCuFoc\PermisDeLucruCuFocType;
use Doctrine\ORM\EntityManager;

class PermisDeLucruCuFocFlow extends FormFlow
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
            'label' => 'Permis De Lucru Cu Foc',
            'type' => new PermisDeLucruCuFocType(),
        );

        return $stepConfig;
    }

    public function getName()
    {
        return 'permis_de_lucru_cu_foc_flow';
    }

}