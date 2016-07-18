<?php

namespace AppBundle\Form\Flow\DocumentForm;

use Craue\FormFlowBundle\Form\FormFlow;
use AppBundle\Form\Type\DocumentForm\DecizieOrganizareActivitateSSM\DecizieOrganizareActivitateSSMType;
use Doctrine\ORM\EntityManager;

class DecizieOrganizareActivitateSSMFlow extends FormFlow
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
            'label' => 'Decizie Organizare Activitate SSM',
            'type' => new DecizieOrganizareActivitateSSMType(),
        );

        return $stepConfig;
    }

    public function getName()
    {
        return 'decizie_organizare_activitate_ssm_flow';
    }

}