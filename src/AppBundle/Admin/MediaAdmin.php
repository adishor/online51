<?php

namespace AppBundle\Admin;

use Sonata\MediaBundle\Admin\ORM\MediaAdmin as SonataMediaAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\EqualType;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Application\Sonata\MediaBundle\Entity\Media;

class MediaAdmin extends SonataMediaAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {

        $datagridMapper
          ->add('name')
          ->add('mediaType', null, array(), 'choice', array(
              'choices' => array(
                  Media::DOCUMENT_TYPE => $this->getTranslator()->trans('media-type.document'),
                  Media::INVOICE_TYPE => $this->getTranslator()->trans('media-type.invoice'),
                  Media::FORM_GENERATED_TYPE => $this->getTranslator()->trans('media-type.form-generated')
            )))
          ->add('deleted', null, array(), null, array('choices_as_values' => true));
    }

    protected function configureListFields(ListMapper $list)
    {

        $list->addIdentifier('name')
          ->add('document')
          ->add('mediaType')
          ->add('deleted');
    }

    protected function configureShowFields(ShowMapper $show)
    {


        $show->add('name')
          ->add('document')
          ->add('deleted')
          ->add('deletedAt');
    }

    public function getFilterParameters()
    {
        $parameters = parent::getFilterParameters();

        if (!array_key_exists("deleted", $parameters)) {
            $parameters['deleted'] = array(
                'type' => EqualType::TYPE_IS_EQUAL,
                'value' => BooleanType::TYPE_NO
            );
        }

        return $parameters;
    }

    public function postUpdate($object)
    {
        $object->setName($object->getProviderMetadata()['filename']);
        $em = $this->configurationPool->getContainer()->get('Doctrine')->getManager();
        $em->persist($object);
        $em->flush();

        parent::postUpdate($object);
    }

}