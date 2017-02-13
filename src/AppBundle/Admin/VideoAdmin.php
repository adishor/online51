<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\EqualType;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Application\Sonata\MediaBundle\Entity\Media;

class VideoAdmin extends AbstractFileAdmin
{

    public function configureFormFields(FormMapper $form)
    {
        $disabled = (boolean)($this->getSubject()->getDeleted());

        $disabledFolder = $disabled || empty($this->getSubject()->getSubdomain());

        $queryMedia = $this->modelManager
          ->getEntityManager('ApplicationSonataMediaBundle:Media')
          ->createQueryBuilder()
          ->select('m')
          ->from('ApplicationSonataMediaBundle:Media', 'm')
          ->leftJoin('m.video', 'v')
          ->where('m.deleted = 0')
          ->andWhere('m.mediaType = :mediaType')
          ->setParameter('mediaType', Media::VIDEO_TYPE)
          ->andWhere('v.id is null or v.id = :videoId')
          ->setParameter('videoId', $this->getSubject()->getId());

        $subdomainsOptions = array(
            'expanded' => false,
            'multiple' => false,
            'by_reference' => true,
            'required' => false,
            'empty_value' => 'No Subdomain',
            'class' => 'AppBundle:SubDomain',
            'choices' => $this->getSubdomainChoices($form),
            'disabled' => $disabled
        );

        $form->add('name', null, array(
              'disabled' => $disabled
            ))
            ->add('creditValue', null, array(
              'disabled' => $disabled
            ))
            ->add('valabilityDays', null, array(
              'disabled' => $disabled
            ))
            ->add('media', 'sonata_type_model', array(
              'query' => $queryMedia,
              'disabled' => $disabled,
              'required' => true,
            ), array(
              'link_parameters' => array(
                  'context' => 'default',
                  'provider' => 'sonata.media.provider.video',
              )
            ))
            ->add('youtubeLink')
            ->add('subdomain', null, $subdomainsOptions)
            ->add('folder', null, array(
                'required' => false,
                'empty_value' => 'No Folder',
//                'disabled' => $disabledFolder,
            ))
        ;
    }

    public function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('name')
          ->add('creditValue')
          ->add('valabilityDays')
          ->add('subdomain')
          ->add('folder')
          ->add('deleted', null, array(), null, array('choices_as_values' => true));
    }

    public function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name')
          ->add('creditValue')
          ->add('valabilityDays')
          ->add('subdomain')
          ->add('folder')
          ->add('deleted');
    }

    public function configureShowFields(ShowMapper $show)
    {
        $show->add('name')
          ->add('creditValue')
          ->add('valabilityDays')
          ->add('subdomain')
          ->add('media', null, array(
              'template' => 'sonata/video_base_show_field.html.twig',
          ))
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


}