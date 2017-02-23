<?php

namespace AppBundle\Form\Flow\DocumentForm;

use Craue\FormFlowBundle\Form\FormFlow;
use AppBundle\Form\Type\DocumentForm\EvidentaGestiuniiDeseurilor\EGDStep0Type;
use AppBundle\Form\Type\DocumentForm\EvidentaGestiuniiDeseurilor\EGDStep1Type;
use AppBundle\Form\Type\DocumentForm\EvidentaGestiuniiDeseurilor\EGDStep2Type;
use AppBundle\Form\Type\DocumentForm\EvidentaGestiuniiDeseurilor\EGDStep3Type;
use AppBundle\Form\Type\DocumentForm\EvidentaGestiuniiDeseurilor\EGDStep4Type;
use AppBundle\Form\Type\DocumentForm\EvidentaGestiuniiDeseurilor\EGDStep5Type;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use AppBundle\Document\EvidentaGestiuniiDeseurilor\EvidentaGestiuniiDeseurilor;

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
//        $creditsUsageId = $this->getRequest()->get('creditsUsageId');
//        $creditsUsage = $this->entityManager->getRepository('AppBundle:CreditsUsage')->find($creditsUsageId);
//        if (empty($creditsUsage)) {
//            throw new AccessDeniedHttpException('formular-documents.access-denied-expired');
//        }

        $stepConfig = array();
        if (EvidentaGestiuniiDeseurilor::$oneStepFormConfig) {
            $stepConfig[] = array(
                'label' => 'Operatia',
                'type' => new EGDStep0Type(),
            );
        }

        $stepConfig[] = array(
            'label' => 'Date Generale',
            'type' => new EGDStep1Type(),
        );
        $stepConfig[] = array(
            'label' => 'Actualizare Generare Deseu',
            'type' => new EGDStep2Type(),
        );
//        $stepConfig[] = array(
//            'label' => 'Stocarea provizorie, tratarea si transportul deseurilor',
//            'type' => new EGDStep3Type(),
//        );
//        if ($creditsUsage->getIsFormConfigFinished()) {
//            $formConfig = json_decode($creditsUsage->getFormConfig());
//            if (isset($formConfig->operatia) && ($formConfig->operatia == 3)) {
//                $stepConfig[] = array(
//                    'label' => 'Valorificarea deseurilor',
//                    'type' => new EGDStep4Type(),
//                );
//            }
//            if (isset($formConfig->operatia) && ($formConfig->operatia == 4)) {
//                $stepConfig[] = array(
//                    'label' => 'Eliminarea deseurilor',
//                    'type' => new EGDStep5Type(),
//                );
//            }
//        } else {
//            $stepConfig[] = array(
//                'label' => 'Valorificarea deseurilor',
//                'type' => new EGDStep4Type(),
//            );
//            $stepConfig[] = array(
//                'label' => 'Eliminarea deseurilor',
//                'type' => new EGDStep5Type(),
//            );
//        }

        $stepConfig[] = array(
            'label' => 'Generare Document Final'
        );

        return $stepConfig;
    }

    public function getName()
    {
        return 'egd_flow';
    }

}