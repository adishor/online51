<?php

namespace AppBundle\Listener;

use Gedmo\SoftDeleteable\SoftDeleteableListener as GedmoSoftDeleteableListener;

class SoftDeleteableListener extends GedmoSoftDeleteableListener
{
    public function onFlush(\Doctrine\Common\EventArgs $args) {

        $ea = $this->getEventAdapter($args);
        $om = $ea->getObjectManager();
        $uow = $om->getUnitOfWork();
        $evm = $om->getEventManager();

        //getScheduledDocumentDeletions
        foreach ($ea->getScheduledObjectDeletions($uow) as $object) {
            $meta = $om->getClassMetadata(get_class($object));
            $config = $this->getConfiguration($om, $meta->name);

            if (isset($config['softDeleteable']) && $config['softDeleteable']) {
                $reflProp = $meta->getReflectionProperty($config['fieldName']);
                $oldValue = $reflProp->getValue($object);

                $evm->dispatchEvent(
                    self::PRE_SOFT_DELETE,
                    $ea->createLifecycleEventArgsInstance($object, $om)
                 );

                $date = new \DateTime();
                $reflProp->setValue($object, $date);

                $om->persist($object);

                $uow->propertyChanged($object, $config['fieldName'], $oldValue, $date);
                $uow->propertyChanged($object, 'deleted', FALSE, TRUE);

                if ($uow instanceof MongoDBUnitOfWork && !method_exists($uow, 'scheduleExtraUpdate')) {
                    $ea->recomputeSingleObjectChangeSet($uow, $meta, $object);
                } else {
                    $uow->scheduleExtraUpdate($object, array(
                        $config['fieldName'] => array($oldValue, $date),
                        'deleted' => array(FALSE, TRUE)
                    ));
                }

                $evm->dispatchEvent(
                    self::POST_SOFT_DELETE,
                    $ea->createLifecycleEventArgsInstance($object, $om)
                );
            }
        }
    }
}

