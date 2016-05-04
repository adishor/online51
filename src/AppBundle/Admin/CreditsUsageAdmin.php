<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\EqualType;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;

class CreditsUsageAdmin extends Admin
{

    protected function configureFormFields(FormMapper $form)
    {
        $disabled = ($this->getSubject()->getDeleted()) ? TRUE : FALSE;

        $queryUser = $this->modelManager
                ->getEntityManager('ApplicationSonataUserBundle:User')
                ->createQueryBuilder()
                ->select('u')
                ->from('ApplicationSonataUserBundle:User', 'u')
                ->where('NOT u.roles LIKE :role')
                ->andWhere('u.deleted = 0')
                ->setParameter('role', '%"ROLE_SUPER_ADMIN"%');

        $queryDocument = $this->modelManager
                ->getEntityManager('ApplicationSonataMediaBundle:Media')
                ->createQueryBuilder()
                ->select('d')
                ->from('ApplicationSonataMediaBundle:Media', 'd')
                ->where('d.deleted = 0');

        $form->add('user', null, array(
                'query_builder' => $queryUser,
                'disabled' => $disabled
            ))
            ->add('document', null, array(
                'query_builder' => $queryDocument,
                'required' => false,
                'disabled' => $disabled
            ))
            ->add('credit', null, array(
                'disabled' => $disabled
            ))
            ->add('mentions', null, array(
                'required' => false,
                'disabled' => $disabled
            ));
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('user')
            ->add('document')
            ->add('credit')
            ->add('createdAt')
            ->add('expireDate')
            ->add('deleted', null, array(), null, array('choices_as_values' => true));
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('id')
            ->add('createdAt')
            ->add('user')
            ->add('document')
            ->add('credit')
            ->add('mentions')
            ->add('expireDate')
            ->add('deleted');
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show->add('user')
            ->add('document')
            ->add('credit')
            ->add('mentions')
            ->add('createdAt')
            ->add('expireDate')
            ->add('deleted')
            ->add('deletedAt');
    }

    public function getTemplate($name)
    {
        if ($name == "edit") {
            return 'sonata/base_edit.html.twig';
        }
        return parent::getTemplate($name);
    }

    public function prePersist($object) {
        $object->setDeleted(false);

        if ($object->getDocument()) {
            $expireDate = new \DateTime();
            $expireDate->add(new \DateInterval('P' . $object->getDocument()->getValabilityDays() . 'D'));
            $object->setExpireDate($expireDate);
        }
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

}