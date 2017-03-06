<?php
/**
 * Created by PhpStorm.
 * User: adriancalugar
 * Date: 06/03/2017
 * Time: 18:36
 */
namespace AppBundle\EventListener;

// in src/MyCompany/MyBundle/Form/CreateVehicleFlow.php
use Craue\FormFlowBundle\Event\GetStepsEvent;
use Craue\FormFlowBundle\Event\PostBindFlowEvent;
use Craue\FormFlowBundle\Event\PostBindRequestEvent;
use Craue\FormFlowBundle\Event\PostBindSavedDataEvent;
use Craue\FormFlowBundle\Event\PostValidateEvent;
use Craue\FormFlowBundle\Event\PreBindEvent;
use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EgdFormFlowListener implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormFlowEvents::PRE_BIND => 'onPreBind',
            FormFlowEvents::GET_STEPS => 'onGetSteps',
            FormFlowEvents::POST_BIND_SAVED_DATA => 'onPostBindSavedData',
            FormFlowEvents::POST_BIND_FLOW => 'onPostBindFlow',
            FormFlowEvents::POST_BIND_REQUEST => 'onPostBindRequest',
            FormFlowEvents::POST_VALIDATE => 'onPostValidate',
        );
    }

    public function onPreBind(PreBindEvent $event) {
        // ...
    }

    public function onGetSteps(GetStepsEvent $event) {
        // ...
    }

    public function onPostBindSavedData(PostBindSavedDataEvent $event) {
        // ...
    }

    public function onPostBindFlow(PostBindFlowEvent $event) {
        // ...
    }

    public function onPostBindRequest(PostBindRequestEvent $event) {
        // ...
    }

    public function onPostValidate(PostValidateEvent $event) {
        // ...
    }

    // ...

}