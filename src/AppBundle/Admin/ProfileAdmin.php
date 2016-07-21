<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;

class ProfileAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
          ->tab('User')
          ->with('General')
          ->add('name', null, array(
            'disabled' => false,
          ))
          ->add('company', null, array(
            'disabled' => false,
          ))
          ->end()
          ->end();
    }
}
