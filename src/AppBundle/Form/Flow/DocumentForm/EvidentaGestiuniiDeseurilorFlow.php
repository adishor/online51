<?php

namespace AppBundle\Form\Flow\DocumentForm;

use Craue\FormFlowBundle\Form\FormFlow;
use AppBundle\Form\Type\DocumentForm\EGDStep1Type;
use AppBundle\Form\Type\DocumentForm\EGDStep2Type;
use AppBundle\Form\Type\DocumentForm\EGDStep3Type;
use AppBundle\Form\Type\DocumentForm\EGDStep4Type;
use AppBundle\Form\Type\DocumentForm\EGDStep5Type;
use Doctrine\ORM\EntityManager;

class EvidentaGestiuniiDeseurilorFlow extends FormFlow
{
    protected $allowDynamicStepNavigation = true;
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function loadStepsConfig()
    {
        $hash = $this->getRequest()->get('hash');
        $creditsUsage = $this->entityManager->getRepository('AppBundle:CreditsUsage')
          ->findOneByFormHashNotExpired($hash);
        if (empty($creditsUsage)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('formular-documents.access-denied-expired'));
        }
        $creditsUsage = reset($creditsUsage);

        $stepConfig = array();
        $stepConfig[] = array(
            'label' => 'Date Generale',
            'type' => new EGDStep1Type(),
        );
        $stepConfig[] = array(
            'label' => 'Generarea deseurilor',
            'type' => new EGDStep2Type(),
        );
        $stepConfig[] = array(
            'label' => 'Stocarea provizorie, tratarea si transportul deseurilor',
            'type' => new EGDStep3Type(),
        );

        if ($creditsUsage->getIsFormConfigFinished()) {
            $formConfig = json_decode($creditsUsage->getFormConfig());
            if (isset($formConfig->operatia) && ($formConfig->operatia == 3)) {
                $stepConfig[] = array(
                    'label' => 'Valorificarea deseurilor',
                    'type' => new EGDStep4Type(),
                );
            }
            if (isset($formConfig->operatia) && ($formConfig->operatia == 4)) {
                $stepConfig[] = array(
                    'label' => 'Eliminarea deseurilor',
                    'type' => new EGDStep5Type(),
                );
            }
        } else {
            $stepConfig[] = array(
                'label' => 'Valorificarea deseurilor',
                'type' => new EGDStep4Type(),
            );
            $stepConfig[] = array(
                'label' => 'Eliminarea deseurilor',
                'type' => new EGDStep5Type(),
            );
        }

        $stepConfig[] = array(
            'label' => 'Generare Document'
        );

        return $stepConfig;
    }

    public function getName()
    {
        return 'egd_flow';
    }

}