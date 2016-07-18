<?php

namespace AppBundle\Form\Flow\DocumentForm;

use Craue\FormFlowBundle\Form\FormFlow;
use AppBundle\Form\Type\DocumentForm\ProcesVerbalSedintaCSSM\ProcesVerbalSedintaCSSMType;
use Doctrine\ORM\EntityManager;

class ProcesVerbalSedintaCSSMFlow extends FormFlow
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
            'label' => 'Proces Verbal Sedinta CSSM',
            'type' => new ProcesVerbalSedintaCSSMType(),
        );

        return $stepConfig;
    }

    public function getName()
    {
        return 'proces_verbal_sedinta_cssm_flow';
    }

}