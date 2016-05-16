<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Application\Sonata\MediaBundle\Entity\Media;

class AdAdmin extends Admin
{

    protected function configureFormFields(FormMapper $form)
    {
        $queryImage = $this->modelManager
          ->getEntityManager('ApplicationSonataMediaBundle:Media')
          ->createQueryBuilder()
          ->select('m')
          ->from('ApplicationSonataMediaBundle:Media', 'm')
          ->leftJoin('m.ad', 'a')
          ->leftJoin('m.user', 'u')
          ->where('m.deleted = 0')
          ->andWhere('m.mediaType = :mediaType')
          ->setParameter('mediaType', Media::IMAGE_TYPE)
          ->andWhere('a.id is null or a.id = :adId')
          ->andWhere('u.id is null')
          ->setParameter('adId', $this->getSubject()->getId());

        $form->add('name')
          ->add('image', 'sonata_type_model', array(
              'query' => $queryImage,
              'required' => true,
            ), array(
              'link_parameters' => array(
                  'context' => 'default',
                  'provider' => 'sonata.media.provider.image',
              )
        ));
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('name');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name')
          ->add('image');
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show->add('name')
          ->add('image', 'sonata_media_type', array(
              'provider' => 'sonata.media.provider.image',
              'context' => 'default',
              'template' => 'sonata/ad_and_user_base_show_field.html.twig',
        ));

    }

}