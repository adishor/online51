<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Application\Sonata\MediaBundle\Entity\Media;
use AppBundle\Entity\Profile;

class ProfileAdmin extends Admin
{

    protected function configureFormFields(FormMapper $formMapper)
    {
        if ($this->hasParentFieldDescription()) {
            // this Admin is embedded
            $parent = $this->getParentFieldDescription()->getAdmin()->getSubject();
            $subject = $parent->getProfile();
        } else {
            // this Admin is not embedded
            $subject = $this->getSubject();
        }

        $imageOptions = array(
            'required' => false,
            'empty_value' => 'No Image'
        );

        if ($subject) {
            $queryImage = $this->modelManager
              ->getEntityManager('ApplicationSonataMediaBundle:Media')
              ->createQueryBuilder()
              ->select('m')
              ->from('ApplicationSonataMediaBundle:Media', 'm')
              ->leftJoin('m.ad', 'a')
              ->leftJoin('m.profile', 'p')
              ->where('m.deleted = 0')
              ->andWhere('m.mediaType = :mediaType')
              ->setParameter('mediaType', Media::IMAGE_TYPE)
              ->andWhere('a.id is null')
              ->andWhere('p.id is null or p.id = :profileId')
              ->setParameter('profileId', $subject->getId());
            $imageOptions['query'] = $queryImage;
        }

        $disabled = (null !== $subject && $subject->getDeleted()) ? TRUE : FALSE;

        $formMapper
          ->tab('User')
          ->with('General')
          ->add('name', null, array(
              'required' => false,
              'disabled' => $disabled
          ))
          ->add('function', 'choice', array(
              'choices' => array(
                  Profile::FUNCTION_EXTERN_JOB => 'user.function.extern_job',
                  Profile::FUNCTION_INTERN_JOB => 'user.function.intern_job',
                  Profile::FUNCTION_APPOINTED_WORKER => 'user.function.appointed_worker',
                  Profile::FUNCTION_ADMINISTRATOR => 'user.function.administrator'
              ),
              'required' => false,
              'disabled' => $disabled
          ))
          ->add('company', null, array(
              'required' => false,
              'disabled' => $disabled
          ))
          ->add('image', 'sonata_type_model', $imageOptions, array(
              'link_parameters' => array(
                  'context' => 'default',
                  'provider' => 'sonata.media.provider.image',
              ))
          )
          ->add('phone')
          ->add('county', null, array(
              'required' => false,
              'disabled' => $disabled
          ))
          ->add('city', null, array(
              'required' => false,
              'disabled' => $disabled
          ))
          ->add('address', null, array(
              'required' => false,
              'disabled' => $disabled
          ))
          ->add('noEmployees', 'choice', array(
              'choices' => array(
                  Profile::NO_EMPLOYEES_0_9 => 'user.employees.0_9',
                  Profile::NO_EMPLOYEES_10_49 => 'user.employees.10_49',
                  Profile::NO_EMPLOYEES_OVER_50 => 'user.employees.over_50'
              ),
              'empty_value' => 'user.employees.select_no',
              'required' => false,
              'disabled' => $disabled
          ))
          ->add('cui', null, array(
              'required' => false,
              'disabled' => $disabled
          ))
          ->add('bank', null, array(
              'required' => false,
              'disabled' => $disabled
          ))
          ->add('iban', null, array(
              'required' => false,
              'disabled' => $disabled
          ))
          ->add('noRegistrationORC', null, array(
              'required' => false,
              'disabled' => $disabled
          ))
          ->add('noCertifiedEmpowerment', null, array(
              'required' => false,
              'disabled' => $disabled
          ))
          ->end()
          ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('name');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name');
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show->add('name');
    }

}