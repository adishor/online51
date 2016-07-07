<?php

namespace AppBundle\Form\Flow;

use Craue\FormFlowBundle\Form\FormFlow;
use AppBundle\Form\Type\RegisterType;
use AppBundle\Form\Type\RegisterOrderType;

class RegisterFlow extends FormFlow
{

    protected function loadStepsConfig()
    {

        $stepConfig = array();
        $stepConfig[] = array(
            'label' => 'Register',
            'type' => new RegisterType(),
        );
        $stepConfig[] = array(
            'label' => 'Order',
            'type' => new RegisterOrderType(),
        );

        return $stepConfig;
    }

    public function getName()
    {
        return 'register_flow';
    }

}