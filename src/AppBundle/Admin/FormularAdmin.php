<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\EqualType;
use Sonata\CoreBundle\Form\Type\BooleanType;
use AppBundle\Entity\Formular;

class FormularAdmin extends Admin
{

    public function configureFormFields(FormMapper $form)
    {
        $disabled = (!$this->getSubject()->getId()) ? TRUE : (($this->getSubject()->getDeleted()) ? TRUE : FALSE);

        $subdomainsOptions = array(
            'expanded' => false,
            'multiple' => false,
            'by_reference' => true,
            'required' => false,
            'class' => 'AppBundle:SubDomain',
            'choices' => $this->getSubdomainChoices($form),
            'empty_value' => 'No Subdomain',
            'disabled' => $disabled
        );

        $form->add('name', null, array(
              'disabled' => ($disabled) ? TRUE : ($this->getSubject()->getId() ? TRUE : FALSE)
          ))
          ->add('creditValue', null, array(
              'disabled' => $disabled
          ))
          ->add('valabilityDays', null, array(
              'disabled' => $disabled
          ))
          ->add('valabilityMonth', 'choice', array(
              'empty_value' => 'month.no_select',
              'choices' => array(
                  Formular::MONTH_JANUARY => 'month.january',
                  Formular::MONTH_FEBRUARY => 'month.february',
                  Formular::MONTH_MARCH => 'month.march',
                  Formular::MONTH_APRIL => 'month.april',
                  Formular::MONTH_MAY => 'month.may',
                  Formular::MONTH_JUNE => 'month.june',
                  Formular::MONTH_JULY => 'month.july',
                  Formular::MONTH_AUGUST => 'month.august',
                  Formular::MONTH_SEPTEMBER => 'month.september',
                  Formular::MONTH_OCTOMBER => 'month.octomber',
                  Formular::MONTH_NOVEMBER => 'month.november',
                  Formular::MONTH_DECEMBER => 'month.december'
              ),
              'disabled' => $disabled,
              'required' => false
          ))
          ->add('subdomain', 'entity', $subdomainsOptions)
          ->add('notifyDays');
    }

    public function configureDatagridFilters(DatagridMapper $filter)
    {
        $translator = $this->getTranslator();

        $filter->add('name')
          ->add('creditValue')
          ->add('valabilityDays')
          ->add('valabilityMonth', null, array(), 'choice', array(
              'choices' => array(
                  Formular::MONTH_JANUARY => $translator->trans('month.january'),
                  Formular::MONTH_FEBRUARY => $translator->trans('month.february'),
                  Formular::MONTH_MARCH => $translator->trans('month.march'),
                  Formular::MONTH_APRIL => $translator->trans('month.april'),
                  Formular::MONTH_MAY => $translator->trans('month.may'),
                  Formular::MONTH_JUNE => $translator->trans('month.june'),
                  Formular::MONTH_JULY => $translator->trans('month.july'),
                  Formular::MONTH_AUGUST => $translator->trans('month.august'),
                  Formular::MONTH_SEPTEMBER => $translator->trans('month.september'),
                  Formular::MONTH_OCTOMBER => $translator->trans('month.octomber'),
                  Formular::MONTH_NOVEMBER => $translator->trans('month.november'),
                  Formular::MONTH_DECEMBER => $translator->trans('month.december')
              )
          ))
          ->add('subdomain')
          ->add('deleted', null, array(), null, array('choices_as_values' => true));
    }

    public function configureListFields(ListMapper $list)
    {
        $translator = $this->getTranslator();

        $list->addIdentifier('name')
          ->add('creditValue')
          ->add('valabilityDays')
          ->add('valabilityMonth', 'choice', array(
              'choices' => array(
                  Formular::MONTH_JANUARY => $translator->trans('month.january'),
                  Formular::MONTH_FEBRUARY => $translator->trans('month.february'),
                  Formular::MONTH_MARCH => $translator->trans('month.march'),
                  Formular::MONTH_APRIL => $translator->trans('month.april'),
                  Formular::MONTH_MAY => $translator->trans('month.may'),
                  Formular::MONTH_JUNE => $translator->trans('month.june'),
                  Formular::MONTH_JULY => $translator->trans('month.july'),
                  Formular::MONTH_AUGUST => $translator->trans('month.august'),
                  Formular::MONTH_SEPTEMBER => $translator->trans('month.september'),
                  Formular::MONTH_OCTOMBER => $translator->trans('month.octomber'),
                  Formular::MONTH_NOVEMBER => $translator->trans('month.november'),
                  Formular::MONTH_DECEMBER => $translator->trans('month.december')
              )
          ))
          ->add('subdomain')
          ->add('deleted');
    }

    public function configureShowFields(ShowMapper $show)
    {
        $translator = $this->getTranslator();

        $show->add('name')
          ->add('creditValue')
          ->add('valabilityDays')
          ->add('valabilityMonth', 'choice', array(
              'choices' => array(
                  Formular::MONTH_JANUARY => $translator->trans('month.january'),
                  Formular::MONTH_FEBRUARY => $translator->trans('month.february'),
                  Formular::MONTH_MARCH => $translator->trans('month.march'),
                  Formular::MONTH_APRIL => $translator->trans('month.april'),
                  Formular::MONTH_MAY => $translator->trans('month.may'),
                  Formular::MONTH_JUNE => $translator->trans('month.june'),
                  Formular::MONTH_JULY => $translator->trans('month.july'),
                  Formular::MONTH_AUGUST => $translator->trans('month.august'),
                  Formular::MONTH_SEPTEMBER => $translator->trans('month.september'),
                  Formular::MONTH_OCTOMBER => $translator->trans('month.octomber'),
                  Formular::MONTH_NOVEMBER => $translator->trans('month.november'),
                  Formular::MONTH_DECEMBER => $translator->trans('month.december')
              )
          ))
          ->add('subdomain')
          ->add('deleted')
          ->add('deletedAt')
          ->add('notifyDays');
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

    public function getTemplate($name)
    {
        if ($name == "edit") {
            return 'sonata/base_edit.html.twig';
        }
        return parent::getTemplate($name);
    }

    public function getSubdomainChoices(FormMapper $formMapper)
    {
        //get all subdomains that are associated
        $domainEm = $formMapper->getAdmin()->getModelManager()->getEntityManager('AppBundle:Domain');
        $domains = $domainEm->getRepository('AppBundle:Domain')->findAll();
        $choices = [];
        foreach ($domains as $domain) {
            $subdomains = [];
            foreach ($domain->getSubdomains() as $subdomain) {
                if (!$subdomain->getDeleted()) {
                    $subdomains[] = $subdomain;
                }
            }
            $choices[$domain->getName()] = $subdomains;
        }
        //get all subdomains that are not associated
        $subdomainEm = $formMapper->getAdmin()->getModelManager()->getEntityManager('AppBundle:SubDomain');
        $noDomainSubdomains = $subdomainEm->getRepository('AppBundle:SubDomain')->createQueryBuilder('s')
          ->where('s.domain is NULL')
          ->andWhere('s.deleted = 0')
          ->getQuery()
          ->getResult();
        $choices['No Domain'] = $noDomainSubdomains;
        return $choices;
    }

}