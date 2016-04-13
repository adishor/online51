<?php

namespace AppBundle\Provider;

use Sonata\MediaBundle\Provider\FileProvider;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;

class DocumentProvider extends FileProvider
{

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper)
    {
        $formMapper
          ->add('title')
          ->add('creditValue')
          ->add('valabilityDays')
          ->add('subdomain', 'sonata_type_model')
          ->add('binaryContent', 'file', array('required' => false));
    }

    /**
     * {@inheritdoc}
     */
    public function buildCreateForm(FormMapper $formMapper)
    {
        $formMapper
          ->add('title')
          ->add('creditValue')
          ->add('valabilityDays')
          ->add('subdomain', 'sonata_type_model')
          ->add('binaryContent', 'file', array(
              'constraints' => array(
                  new NotBlank(),
                  new NotNull(),
              ),
        ));
    }

    public function getTemplate($name)
    {
        if ($name == "list") {
            return 'sonata/base_list.html.twig';
        }
        return parent::getTemplate($name);
    }

}