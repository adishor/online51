<?php

namespace AppBundle\Provider;

use Sonata\MediaBundle\Provider\FileProvider;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;

class VideoFileProvider extends FileProvider
{

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper)
    {
        $disabled = ($formMapper->getAdmin()->getSubject()->getDeleted()) ? TRUE : FALSE;

        $formMapper
          ->add('name', null, array(
              'disabled' => $disabled
          ))
          ->add('binaryContent', 'file', array(
              'required' => false,
              'disabled' => $disabled
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildCreateForm(FormMapper $formMapper)
    {
        $formMapper
          ->add('binaryContent', 'file', array(
              'constraints' => array(
                  new NotBlank(),
                  new NotNull(),
                  new File(array(
                      'maxSize' => '128M'
                    )
                  )
              ),
        ));
    }

}